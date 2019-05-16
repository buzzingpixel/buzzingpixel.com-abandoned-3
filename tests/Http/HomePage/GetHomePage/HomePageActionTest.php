<?php

declare(strict_types=1);

namespace Tests\Http\HomePage\GetHomePage;

use App\Common\Http\StandardPageResponder;
use App\Http\HomePage\GetHomePage\HomePageAction;
use BuzzingPixel\Scribble\ScribbleApiContract;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class HomePageActionTest extends TestCase
{
    /** @var MockObject&ResponseInterface */
    private $response;
    /** @var MockObject&ScribbleApiContract */
    private $scribbleApi;
    /** @var MockObject&StandardPageResponder */
    private $standardPageResponder;
    /** @var HomePageAction */
    private $homePageAction;

    /**
     * @throws Throwable
     */
    protected function setUp() : void
    {
        $this->response = $this->createMock(ResponseInterface::class);

        $this->scribbleApi = $this->createMock(ScribbleApiContract::class);

        $this->standardPageResponder = $this->createMock(StandardPageResponder::class);

        $this->standardPageResponder->method('createResponseBasedOnInput')
            ->willReturn($this->response);

        $this->homePageAction = new HomePageAction(
            $this->scribbleApi,
            $this->standardPageResponder
        );
    }

    /**
     * @throws Throwable
     */
    public function testInvoke() : void
    {
        $this->scribbleApi->expects(self::once())
            ->method('getContentFromPath')
            ->with(
                self::equalTo('HomePage'),
                self::equalTo($this->standardPageResponder)
            );

        self::assertSame(
            $this->response,
            $this->homePageAction->__invoke()
        );
    }
}
