<?php

declare(strict_types=1);

namespace Tests\Http\Error;

use App\Http\Error\ErrorPageAction;
use App\Http\Error\ErrorPageMiddleware;
use corbomite\http\exceptions\Http404Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Console\Exception\LogicException;
use Throwable;
use Zend\Diactoros\ResponseFactory;

class ErrorPageMiddlewareTest extends TestCase
{
    /** @var MockObject&ErrorPageAction */
    private $errorPageAction;
    /** @var MockObject&ServerRequestInterface */
    private $request;
    /** @var MockObject&RequestHandlerInterface */
    private $handler;
    /** @var ResponseInterface */
    private $responseOne;
    /** @var ResponseInterface */
    private $responseTwo;
    /** @var ErrorPageMiddleware */
    private $service;

    /**
     * @throws Throwable
     */
    protected function setUp() : void
    {
        $this->errorPageAction = $this->createMock(ErrorPageAction::class);

        $this->request = $this->createMock(ServerRequestInterface::class);

        $this->handler = $this->createMock(RequestHandlerInterface::class);

        $this->responseOne = (new ResponseFactory())->createResponse(200);

        $this->responseTwo = (new ResponseFactory())->createResponse(200);

        $this->service = new ErrorPageMiddleware($this->errorPageAction);
    }

    public function testHandlerResponds() : void
    {
        $this->handler->expects(self::once())
            ->method('handle')
            ->with(self::equalTo($this->request))
            ->willReturn($this->responseOne);

        self::assertSame(
            $this->responseOne,
            $this->service->process($this->request, $this->handler)
        );
    }

    public function testHandlerThrowsException() : void
    {
        $this->handler->expects(self::once())
            ->method('handle')
            ->with(self::equalTo($this->request))
            ->willThrowException(new LogicException());

        $this->errorPageAction->expects(self::once())
            ->method('__invoke')
            ->with(self::equalTo(500))
            ->willReturn($this->responseTwo);

        self::assertSame(
            $this->responseTwo,
            $this->service->process($this->request, $this->handler)
        );
    }

    public function testHandlerThrowsHttp404Exception() : void
    {
        $this->handler->expects(self::once())
            ->method('handle')
            ->with(self::equalTo($this->request))
            ->willThrowException(new Http404Exception());

        $this->errorPageAction->expects(self::once())
            ->method('__invoke')
            ->with(self::equalTo(404))
            ->willReturn($this->responseTwo);

        self::assertSame(
            $this->responseTwo,
            $this->service->process($this->request, $this->handler)
        );
    }
}
