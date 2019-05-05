<?php

declare(strict_types=1);

namespace Tests\Http\HomePage\GetHomePage;

use App\Common\Http\StandardPageResponder;
use App\Http\HomePage\GetHomePage\HomePageAction;
use BuzzingPixel\Scribble\ScribbleApiContract;
use corbomite\di\Di;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Throwable;

class HomePageActionTest extends TestCase
{
    /**
     * @throws Throwable
     */
    public function testHomePageAction() : void
    {
        $response = Di::diContainer()->get(ResponseFactoryInterface::class)->createResponse();

        /** @var MockObject&StandardPageResponder $standardPageResponder */
        $standardPageResponder = $this->createMock(StandardPageResponder::class);

        $standardPageResponder->expects(self::once())
            ->method('createResponseBasedOnInput')
            ->willReturn($response);

        /** @var MockObject&ScribbleApiContract $scribbleApi */
        $scribbleApi = $this->createMock(ScribbleApiContract::class);

        $scribbleApi->expects(self::once())
            ->method('getContentFromPath')
            ->with(
                self::equalTo('HomePage'),
                self::equalTo($standardPageResponder)
            );

        $action = new HomePageAction(
            $scribbleApi,
            $standardPageResponder
        );

        self::assertSame($action(), $response);
    }
}
