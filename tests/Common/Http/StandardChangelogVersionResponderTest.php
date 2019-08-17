<?php

declare(strict_types=1);

namespace Tests\Common\Http;

use App\Common\Http\StandardChangelogVersionResponder;
use corbomite\di\Di;
use corbomite\http\exceptions\Http404Exception;
use corbomite\twig\TwigEnvironment;
use Exception;
use MJErwin\ParseAChangelog\Release;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Throwable;
use function array_keys;

class StandardChangelogVersionResponderTest extends TestCase
{
    /** @var string|null */
    private $template;

    /** @var mixed[]|null */
    private $vars;

    /** @var TwigEnvironment|MockObject */
    private $twigEnvironmentMock;

    /** @var Release|MockObject */
    private $release;

    /** @var StandardChangelogVersionResponder */
    private $object;

    /**
     * @throws Throwable
     */
    protected function setUp() : void
    {
        $that = $this;

        $this->template = null;

        $this->vars = null;

        /** @var TwigEnvironment&MockObject $twigEnvironmentMock */
        $twigEnvironmentMock = $this->createMock(TwigEnvironment::class);

        $this->twigEnvironmentMock = $twigEnvironmentMock;

        $twigEnvironmentMock->method('renderAndMinify')
            ->willReturnCallback(static function ($template, $vars) use ($that) : string {
                $that->template = $template;

                $that->vars = $vars;

                return 'renderAndMinifyReturn';
            });

        /** @var Release&MockObject $release */
        $release = $this->createMock(Release::class);

        $release->method('getVersion')->willReturn('1.2.3');

        $this->release = $release;

        $this->object = new StandardChangelogVersionResponder(
            Di::diContainer()->get(ResponseFactoryInterface::class),
            $twigEnvironmentMock
        );
    }

    /**
     * @throws Throwable
     */
    public function testResponseWhenHasFailed() : void
    {
        self::expectException(Http404Exception::class);

        $this->object->parsingFailed();

        $this->object->createResponseBasedOnInput(
            'baseVal',
            'metaTitleVal',
            'titlesAreasGlobalVal',
            'navAreasGlobalVal',
            'breacrumbBaseTitleVal'
        );
    }

    /**
     * @throws Throwable
     */
    public function testResponseWhenNoRelease() : void
    {
        self::expectException(Http404Exception::class);

        $this->object->createResponseBasedOnInput(
            'baseVal',
            'metaTitleVal',
            'titlesAreasGlobalVal',
            'navAreasGlobalVal',
            'breacrumbBaseTitleVal'
        );
    }

    /**
     * @throws Throwable
     */
    public function testResponseCatchErrorRendering() : void
    {
        self::expectException(Http404Exception::class);

        $this->twigEnvironmentMock->method('renderAndMinify')
            ->willThrowException(new Exception());

        $this->object->parsingSucceeded($this->release);

        $this->object->createResponseBasedOnInput(
            'baseVal',
            'metaTitleVal',
            'titlesAreasGlobalVal',
            'navAreasGlobalVal',
            'breacrumbBaseTitleVal'
        );
    }

    /**
     * @throws Throwable
     */
    public function testResponse() : void
    {
        $this->object->parsingSucceeded($this->release);

        $response = $this->object->createResponseBasedOnInput(
            'baseVal',
            'metaTitleVal',
            'titlesAreasGlobalVal',
            'navAreasGlobalVal',
            'breacrumbBaseTitleVal'
        );

        $allowedVars = [
            'release',
            'metaTitle',
            'titleAreasGlobal',
            'navAreasGlobal',
            'activeHref',
            'base',
            'breadcrumbBaseTitle',
        ];

        self::assertSame(
            $allowedVars,
            array_keys($this->vars)
        );

        self::assertSame(200, $response->getStatusCode());

        self::assertSame(
            [
                'Content-Type' => ['text/html'],
            ],
            $response->getHeaders()
        );

        self::assertSame(
            'renderAndMinifyReturn',
            (string) $response->getBody()
        );

        self::assertSame('ChangelogVersion.twig', $this->template);

        self::assertSame($this->release, $this->vars['release']);

        self::assertSame('1.2.3 | metaTitleVal', $this->vars['metaTitle']);

        self::assertSame('titlesAreasGlobalVal', $this->vars['titleAreasGlobal']);

        self::assertSame('navAreasGlobalVal', $this->vars['navAreasGlobal']);

        self::assertSame('baseVal', $this->vars['activeHref']);

        self::assertSame('baseVal', $this->vars['base']);

        self::assertSame('breacrumbBaseTitleVal', $this->vars['breadcrumbBaseTitle']);
    }
}
