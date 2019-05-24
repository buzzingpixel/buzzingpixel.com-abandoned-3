<?php

declare(strict_types=1);

namespace App\Http\Error;

use corbomite\twig\TwigEnvironment;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class ErrorPageResponder
{
    /** @var ResponseFactoryInterface */
    private $responseFactory;
    /** @var TwigEnvironment */
    private $twig;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        TwigEnvironment $twig
    ) {
        $this->responseFactory = $responseFactory;
        $this->twig            = $twig;
    }

    public function createResponseBasedOnStatusCode(int $errorCode) : ResponseInterface
    {
        $response = $this->responseFactory->createResponse($errorCode)
            ->withHeader('Content-Type', 'text/html');

        if ($errorCode === 404) {
            /** @noinspection PhpUnhandledExceptionInspection */
            $response->getBody()->write(
                $this->twig->renderAndMinify('404.twig')
            );

            return $response;
        }

        /** @noinspection PhpUnhandledExceptionInspection */
        $response->getBody()->write(
            $this->twig->renderAndMinify('500.twig')
        );

        return $response;
    }
}
