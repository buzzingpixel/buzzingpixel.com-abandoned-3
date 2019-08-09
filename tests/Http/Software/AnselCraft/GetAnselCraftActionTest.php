<?php

declare(strict_types=1);

namespace Tests\Http\Software\AnselCraft;

use App\Common\Http\StandardPageResponder;
use App\Http\Software\AnselCraft\GetAnselCraftAction;
use BuzzingPixel\Scribble\ScribbleApiContract;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class GetAnselCraftActionTest extends TestCase
{
    /** @var MockObject&ResponseInterface */
    private $response;
    /** @var MockObject&ScribbleApiContract */
    private $scribbleApi;
    /** @var MockObject&StandardPageResponder */
    private $standardPageResponder;
    /** @var GetAnselCraftAction */
    private $objectToTest;

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

        $this->objectToTest = new GetAnselCraftAction(
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
                self::equalTo('Software/AnselCraft'),
                self::equalTo($this->standardPageResponder)
            );

        self::assertSame(
            $this->response,
            $this->objectToTest->__invoke()
        );
    }
}
