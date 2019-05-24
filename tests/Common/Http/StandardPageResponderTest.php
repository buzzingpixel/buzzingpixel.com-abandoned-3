<?php

declare(strict_types=1);

namespace Tests\Common\Http;

use App\Common\Http\StandardPageResponder;
use BuzzingPixel\Scribble\Services\GetContentFromFile\Content;
use BuzzingPixel\Scribble\Services\GetContentFromPath\ContentCollection;
use corbomite\di\Di;
use corbomite\http\exceptions\Http500Exception;
use corbomite\twig\TwigEnvironment;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Throwable;

class StandardPageResponderTest extends TestCase
{
    /** @var string|null */
    private $template;

    /** @var mixed[]|null */
    private $vars;

    /** @var StandardPageResponder */
    private $standardPageResponder;

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

        $twigEnvironmentMock->method('renderAndMinify')
            ->willReturnCallback(static function ($template, $vars) use ($that) : string {
                $that->template = $template;

                $that->vars = $vars;

                return 'renderAndMinifyReturn';
            });

        $this->standardPageResponder = new StandardPageResponder(
            Di::diContainer()->get(ResponseFactoryInterface::class),
            $twigEnvironmentMock
        );
    }

    public function testAssertUnableToParsePathThrowsLogicException() : void
    {
        self::expectException(LogicException::class);
        self::expectExceptionMessage('Unable to find content');

        $this->standardPageResponder->unableToParsePath();
    }

    public function testAssertNoResultsThrowsLogicException() : void
    {
        self::expectException(LogicException::class);
        self::expectExceptionMessage('Unable to find content');

        $this->standardPageResponder->noResults();
    }

    /**
     * @throws Throwable
     */
    public function testCreateResponseBasedOnInputThrowsExceptionWhenNoContent() : void
    {
        self::expectException(Http500Exception::class);

        $this->standardPageResponder->createResponseBasedOnInput();
    }

    /**
     * @throws Throwable
     */
    public function testCreateResponseBasedOnInputReturnsResponse() : void
    {
        $content = new Content(
            'testMarkdown',
            'testHtml',
            ['foo' => 'bar']
        );

        $index = new Content(
            '',
            '',
            ['baseNameNoExtension' => 'index']
        );

        $contentCollection = new ContentCollection([
            $content,
            $index,
        ]);

        $this->standardPageResponder->contentRetrieved($contentCollection);

        $response = $this->standardPageResponder->createResponseBasedOnInput();

        self::assertEquals(
            'text/html',
            $response->getHeaderLine('Content-Type')
        );

        $response->getBody()->rewind();

        self::assertEquals(
            'renderAndMinifyReturn',
            $response->getBody()->getContents()
        );

        self::assertEquals('StandardPage.twig', $this->template);

        self::assertSame($index, $this->vars['index']);

        self::assertSame(
            $content,
            $this->vars['contentCollection']->first()
        );
    }
}
