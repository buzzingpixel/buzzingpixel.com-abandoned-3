<?php

declare(strict_types=1);

namespace Tests\Http\Error;

use App\Http\Error\ErrorPageResponder;
use corbomite\di\Di;
use corbomite\twig\TwigEnvironment;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseFactoryInterface;
use Throwable;

class ErrorPageResponderTest extends TestCase
{
    /** @var string|null */
    private $template;
    /** @var ErrorPageResponder */
    private $service;

    /**
     * @throws Throwable
     */
    protected function setUp() : void
    {
        $self = $this;

        /** @var TwigEnvironment&MockObject $twigEnvironmentMock */
        $twigEnvironmentMock = $this->createMock(TwigEnvironment::class);

        $twigEnvironmentMock->method('renderAndMinify')
            ->willReturnCallback(static function ($template) use ($self) : string {
                $self->template = $template;

                return 'renderAndMinifyReturn';
            });

        $this->service = new ErrorPageResponder(
            Di::diContainer()->get(ResponseFactoryInterface::class),
            $twigEnvironmentMock
        );
    }

    public function test404Response() : void
    {
        $response = $this->service->createResponseBasedOnStatusCode(404);

        self::assertEquals(404, $response->getStatusCode());

        self::assertEquals(
            'text/html',
            $response->getHeaderLine('Content-Type')
        );

        $response->getBody()->rewind();

        self::assertEquals(
            'renderAndMinifyReturn',
            $response->getBody()->getContents()
        );

        self::assertEquals('404.twig', $this->template);
    }

    public function test500Response() : void
    {
        $response = $this->service->createResponseBasedOnStatusCode(500);

        self::assertEquals(500, $response->getStatusCode());

        self::assertEquals(
            'text/html',
            $response->getHeaderLine('Content-Type')
        );

        $response->getBody()->rewind();

        self::assertEquals(
            'renderAndMinifyReturn',
            $response->getBody()->getContents()
        );

        self::assertEquals('500.twig', $this->template);
    }
}
