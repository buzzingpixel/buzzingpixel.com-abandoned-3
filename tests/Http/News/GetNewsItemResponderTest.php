<?php

declare(strict_types=1);

namespace Tests\Http\News;

use App\Common\Infrastructure\FileContent\ScribbleApi;
use App\Http\News\GetNewsItemResponder;
use BuzzingPixel\Scribble\Services\GetContentFromFile\Content;
use corbomite\di\Di;
use corbomite\http\exceptions\Http404Exception;
use corbomite\twig\TwigEnvironment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Throwable;
use Zend\Diactoros\ResponseFactory;

class GetNewsItemResponderTest extends TestCase
{
    /** @var TwigEnvironment&MockObject */
    private $twig;
    /** @var GetNewsItemResponder */
    private $getNewsItemResponder;

    protected function setUp() : void
    {
        $this->twig = $this->createMock(TwigEnvironment::class);

        $this->getNewsItemResponder = new GetNewsItemResponder(
            new ResponseFactory(),
            $this->twig
        );

        // For test coverage
        $this->getNewsItemResponder->unableToParsePath();
        $this->getNewsItemResponder->noResults();
    }

    /**
     * @throws Throwable
     */
    public function testNoEntry() : void
    {
        $this->expectException(Http404Exception::class);

        $this->getNewsItemResponder->createResponse();
    }

    /**
     * @throws Throwable
     */
    public function testNoMatchingEntry() : void
    {
        $this->getNewsItemResponder->setSlug('fooSlugTest');

        Di::diContainer()->get(ScribbleApi::class)->getContentPathCollection(
            'News',
            $this->getNewsItemResponder
        );

        $this->expectException(Http404Exception::class);

        $this->getNewsItemResponder->createResponse();
    }

    /**
     * @throws Throwable
     */
    public function testMatchingEntry() : void
    {
        $this->getNewsItemResponder->setSlug('ee-4-add-on-compatibility');

        Di::diContainer()->get(ScribbleApi::class)->getContentPathCollection(
            'News',
            $this->getNewsItemResponder
        );

        $holder = new stdClass();

        $holder->template = null;
        $holder->entry    = null;

        $this->twig->expects(self::once())
            ->method('renderAndMinify')
            ->willReturnCallback(static function (string $template, array $context) use ($holder) {
                $holder->template = $template;

                $holder->entry = $context['entry'];

                return 'fooRenderedTemplate';
            });

        $response = $this->getNewsItemResponder->createResponse();

        self::assertSame(200, $response->getStatusCode());

        self::assertSame('text/html', $response->getHeaderLine('Content-Type'));

        self::assertSame('fooRenderedTemplate', (string) $response->getBody());

        self::assertSame('NewsItem.twig', $holder->template);

        self::assertInstanceOf(Content::class, $holder->entry);
    }
}
