<?php

declare(strict_types=1);

namespace Tests\Http\News;

use App\Http\News\GetNewsItemAction;
use App\Http\News\GetNewsItemResponder;
use BuzzingPixel\Scribble\ScribbleApiContract;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Zend\Diactoros\ResponseFactory;

class GetNewsItemActionTest extends TestCase
{
    /** @var MockObject&ScribbleApiContract */
    private $scribbleApi;
    /** @var GetNewsItemResponder&MockObject */
    private $responder;
    /** @var MockObject&ServerRequestInterface */
    private $serverRequest;
    /** @var GetNewsItemAction */
    private $getNewsItemAction;

    protected function setUp() : void
    {
        $this->scribbleApi = $this->createMock(ScribbleApiContract::class);

        $this->responder = $this->createMock(GetNewsItemResponder::class);

        $this->serverRequest = $this->createMock(ServerRequestInterface::class);

        $this->getNewsItemAction = new GetNewsItemAction(
            $this->scribbleApi,
            $this->responder
        );
    }

    /**
     * @throws Throwable
     */
    public function testInvoke() : void
    {
        $this->serverRequest->expects(self::once())
            ->method('getAttribute')
            ->with(self::equalTo('slug'))
            ->willReturn('fooSlug');

        $this->responder->expects(self::once())
            ->method('setSlug')
            ->with(self::equalTo('fooSlug'));

        $this->scribbleApi->expects(self::once())
            ->method('getContentPathCollection')
            ->with(
                self::equalTo('News'),
                self::equalTo($this->responder)
            );

        $responseInterface = (new ResponseFactory())->createResponse();

        $this->responder->expects(self::once())
            ->method('createResponse')
            ->willReturn($responseInterface);

        self::assertSame(
            $responseInterface,
            $this->getNewsItemAction->__invoke($this->serverRequest)
        );
    }
}
