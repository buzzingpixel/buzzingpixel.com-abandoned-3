<?php

declare(strict_types=1);

namespace Tests\Http\News;

use App\Http\News\GetNewsResponder;
use BuzzingPixel\Scribble\Services\GetContentPathCollection\ContentPathCollection;
use corbomite\http\exceptions\Http404Exception;
use corbomite\twig\TwigEnvironment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Throwable;
use Zend\Diactoros\ResponseFactory;

class GetNewsResponderTest extends TestCase
{
    /** @var GetNewsResponder */
    private $getNewsResponder;

    /** @var MockObject&TwigEnvironment */
    private $twig;
    /** @var MockObject&ContentPathCollection */
    private $collection;

    /**
     * @throws Throwable
     */
    protected function setUp() : void
    {
        $responseFactory = new ResponseFactory();

        $this->twig = $this->createMock(TwigEnvironment::class);

        $this->getNewsResponder = new GetNewsResponder(
            $responseFactory,
            $this->twig
        );

        $this->collection = $this->createMock(ContentPathCollection::class);

        // For code coverage, these methods don't do anything for us in this case
        $this->getNewsResponder->unableToParsePath();
        $this->getNewsResponder->noResults();
    }

    /**
     * @throws Throwable
     */
    public function testNoCollection() : void
    {
        self::expectException(Http404Exception::class);

        $this->getNewsResponder->createResponse(123, 123);
    }

    /**
     * @throws Throwable
     */
    public function testPageIs0() : void
    {
        self::expectException(Http404Exception::class);

        $this->collection->expects(self::once())
            ->method('reverseSortOrder')
            ->willReturn($this->collection);

        $this->getNewsResponder->contentRetrieved($this->collection);

        $this->getNewsResponder->createResponse(123, 0);
    }

    /**
     * @throws Throwable
     */
    public function testPageIs1NoSubSet() : void
    {
        self::expectException(Http404Exception::class);

        $this->collection->expects(self::once())
            ->method('reverseSortOrder')
            ->willReturn($this->collection);

        $this->collection->expects(self::once())
            ->method('subSet')
            ->with(
                self::equalTo(123),
                self::equalTo(0)
            )
            ->willReturn(null);

        $this->getNewsResponder->contentRetrieved($this->collection);

        $this->getNewsResponder->createResponse(123, 1);
    }

    /**
     * @throws Throwable
     */
    public function testPageIs22() : void
    {
        $limit = 123;

        $page = 22;

        $this->collection->expects(self::once())
            ->method('reverseSortOrder')
            ->willReturn($this->collection);

        $this->collection->expects(self::once())
            ->method('subSet')
            ->with(
                self::equalTo($limit),
                self::equalTo(($page - 1) * $limit)
            )
            ->willReturn($this->collection);

        $this->twig->expects(self::once())
            ->method('renderAndMinify')
            ->with(
                self::equalTo('NewsIndex.twig'),
                self::equalTo(['collection' => $this->collection])
            )
            ->willReturn('fooTwigRendered');

        $this->getNewsResponder->contentRetrieved($this->collection);

        $response = $this->getNewsResponder->createResponse($limit, $page);

        self::assertEquals(200, $response->getStatusCode());

        self::assertEquals(
            'text/html',
            $response->getHeaderLine('Content-Type')
        );

        $response->getBody()->rewind();

        self::assertEquals(
            'fooTwigRendered',
            $response->getBody()->getContents()
        );
    }
}
