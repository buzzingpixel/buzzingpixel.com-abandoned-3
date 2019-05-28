<?php

declare(strict_types=1);

namespace Tests\Http\News;

use App\Http\News\GetNewsAction;
use App\Http\News\GetNewsResponder;
use BuzzingPixel\Scribble\ScribbleApiContract;
use corbomite\http\exceptions\Http404Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class GetNewsActionTest extends TestCase
{
    /** @var GetNewsAction */
    private $getNewsAction;

    /** @var MockObject&ScribbleApiContract */
    private $scribbleApi;
    /** @var MockObject&GetNewsResponder */
    private $responder;
    /** @var MockObject&ServerRequestInterface */
    private $request;

    /**
     * @throws Throwable
     */
    protected function setUp() : void
    {
        $this->scribbleApi = $this->createMock(ScribbleApiContract::class);

        $this->responder = $this->createMock(GetNewsResponder::class);

        $this->request = $this->createMock(ServerRequestInterface::class);

        $this->getNewsAction = new GetNewsAction(
            $this->scribbleApi,
            $this->responder
        );
    }

    /**
     * @throws Throwable
     */
    public function testInvokeWhenPageIs0() : void
    {
        $this->scribbleApi->expects(self::never())->method(self::anything());

        $this->responder->expects(self::never())->method(self::anything());

        $this->request->expects(self::once())
            ->method('getAttribute')
            ->with(self::equalTo('page'))
            ->willReturn('0');

        self::expectException(Http404Exception::class);

        $this->getNewsAction->__invoke($this->request);
    }

    /**
     * @throws Throwable
     */
    public function testInvokeWhenPageIs1() : void
    {
        $this->scribbleApi->expects(self::never())->method(self::anything());

        $this->responder->expects(self::never())->method(self::anything());

        $this->request->expects(self::once())
            ->method('getAttribute')
            ->with(self::equalTo('page'))
            ->willReturn('1');

        self::expectException(Http404Exception::class);

        $this->getNewsAction->__invoke($this->request);
    }

    /**
     * @throws Throwable
     */
    public function testInvokeWhenPageIsNull() : void
    {
        $this->scribbleApi->expects(self::once())
            ->method('getContentPathCollection')
            ->with(
                self::equalTo('News'),
                self::equalTo($this->responder)
            );

        $this->responder->expects(self::once())
            ->method('createResponse')
            ->with(
                self::equalTo(10),
                self::equalTo(1)
            );

        $this->request->expects(self::once())
            ->method('getAttribute')
            ->with(self::equalTo('page'))
            ->willReturn(null);

        $this->getNewsAction->__invoke($this->request);
    }

    /**
     * @throws Throwable
     */
    public function testInvokeWhenPageIsTwo() : void
    {
        $this->scribbleApi->expects(self::once())
            ->method('getContentPathCollection')
            ->with(
                self::equalTo('News'),
                self::equalTo($this->responder)
            );

        $this->responder->expects(self::once())
            ->method('createResponse')
            ->with(
                self::equalTo(10),
                self::equalTo(2)
            );

        $this->request->expects(self::once())
            ->method('getAttribute')
            ->with(self::equalTo('page'))
            ->willReturn('2');

        $this->getNewsAction->__invoke($this->request);
    }

    /**
     * @throws Throwable
     */
    public function testInvokeWhenPageIsFifteen() : void
    {
        $this->scribbleApi->expects(self::once())
            ->method('getContentPathCollection')
            ->with(
                self::equalTo('News'),
                self::equalTo($this->responder)
            );

        $this->responder->expects(self::once())
            ->method('createResponse')
            ->with(
                self::equalTo(10),
                self::equalTo(15)
            );

        $this->request->expects(self::once())
            ->method('getAttribute')
            ->with(self::equalTo('page'))
            ->willReturn('15');

        $this->getNewsAction->__invoke($this->request);
    }
}
