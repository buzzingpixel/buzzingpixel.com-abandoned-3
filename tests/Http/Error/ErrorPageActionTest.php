<?php

declare(strict_types=1);

namespace Tests\Http\Error;

use App\Http\Error\ErrorPageAction;
use App\Http\Error\ErrorPageResponder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Throwable;
use Zend\Diactoros\ResponseFactory;

class ErrorPageActionTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testInvoke() : void
    {
        /** @var MockObject&ErrorPageResponder $responder */
        $responder = $this->createMock(ErrorPageResponder::class);

        $response = (new ResponseFactory())->createResponse(123);

        $responder->expects(self::once())
            ->method('createResponseBasedOnStatusCode')
            ->with(self::equalTo(123))
            ->willReturn($response);

        self::assertSame(
            $response,
            (new ErrorPageAction($responder))(123)
        );
    }
}
