<?php

declare(strict_types=1);

namespace App\Http\Error;

use Psr\Http\Message\ResponseInterface;

class ErrorPageAction
{
    /** @var ErrorPageResponder */
    private $responder;

    public function __construct(ErrorPageResponder $responder)
    {
        $this->responder = $responder;
    }

    public function __invoke(int $code) : ResponseInterface
    {
        return $this->responder->createResponseBasedOnStatusCode($code);
    }
}
