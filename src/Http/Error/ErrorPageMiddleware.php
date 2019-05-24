<?php

declare(strict_types=1);

namespace App\Http\Error;

use corbomite\http\exceptions\Http404Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class ErrorPageMiddleware implements MiddlewareInterface
{
    /** @var ErrorPageAction */
    private $errorPageAction;

    public function __construct(ErrorPageAction $errorPageAction)
    {
        $this->errorPageAction = $errorPageAction;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (Throwable $e) {
            $code = 500;

            if ($e instanceof Http404Exception ||
                $e->getPrevious() instanceof Http404Exception
            ) {
                $code = 404;
            }

            return ($this->errorPageAction)($code);
        }
    }
}
