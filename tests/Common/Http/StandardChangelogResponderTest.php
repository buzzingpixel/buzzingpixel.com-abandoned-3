<?php

declare(strict_types=1);

namespace Tests\Common\Http;

use App\Common\Http\StandardChangelogResponder;
use App\Common\Pagination\Pagination;
use corbomite\di\Di;
use corbomite\http\exceptions\Http404Exception;
use corbomite\twig\TwigEnvironment;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Throwable;
use function array_keys;

class StandardChangelogResponderTest extends TestCase
{
    /** @var string|null */
    private $template;

    /** @var mixed[]|null */
    private $vars;

    /** @var TwigEnvironment|MockObject */
    private $twigEnvironmentMock;

    /** @var StandardChangelogResponder */
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

        $this->object = new StandardChangelogResponder(
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
            22,
            1,
            'baseVal',
            'metaTitleVal',
            'titlesAreasGlobalVal',
            'navAreasGlobalVal'
        );
    }

    /**
     * @throws Throwable
     */
    public function testResponseWhenPageIs0() : void
    {
        self::expectException(Http404Exception::class);

        $this->object->createResponseBasedOnInput(
            22,
            0,
            'baseVal',
            'metaTitleVal',
            'titlesAreasGlobalVal',
            'navAreasGlobalVal'
        );
    }

    /**
     * @throws Throwable
     */
    public function testResponseWhenNoPageContent() : void
    {
        self::expectException(Http404Exception::class);

        $this->object->parsingSucceeded([
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
        ]);

        $this->object->createResponseBasedOnInput(
            22,
            2,
            'baseVal',
            'metaTitleVal',
            'titlesAreasGlobalVal',
            'navAreasGlobalVal'
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

        $this->object->parsingSucceeded([
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
        ]);

        $this->object->createResponseBasedOnInput(
            10,
            2,
            'baseVal',
            'metaTitleVal',
            'titlesAreasGlobalVal',
            'navAreasGlobalVal'
        );
    }

    /**
     * @throws Throwable
     */
    public function testResponse() : void
    {
        $allContent =[
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
        ];

        $this->object->parsingSucceeded($allContent);

        $response = $this->object->createResponseBasedOnInput(
            10,
            2,
            'baseVal',
            'metaTitleVal',
            'titlesAreasGlobalVal',
            'navAreasGlobalVal'
        );

        $allowedVars = [
            'pageContent',
            'allContent',
            'pagination',
            'metaTitle',
            'titleAreasGlobal',
            'navAreasGlobal',
            'activeHref',
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

        self::assertSame('ChangelogIndex.twig', $this->template);

        self::assertSame(
            [
                '11',
                '12',
            ],
            $this->vars['pageContent']
        );

        self::assertSame($allContent, $this->vars['allContent']);

        self::assertInstanceOf(
            Pagination::class,
            $this->vars['pagination']
        );

        self::assertSame('metaTitleVal', $this->vars['metaTitle']);

        self::assertSame('titlesAreasGlobalVal', $this->vars['titleAreasGlobal']);

        self::assertSame('navAreasGlobalVal', $this->vars['navAreasGlobal']);

        self::assertSame('baseVal', $this->vars['activeHref']);

        /** @var Pagination $pagination */
        $pagination = $this->vars['pagination'];

        self::assertSame('/baseVal', $pagination->base());

        self::assertSame(2, $pagination->currentPage());

        self::assertSame(10, $pagination->perPage());

        self::assertSame(12, $pagination->totalResults());
    }
}
