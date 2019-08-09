<?php

declare(strict_types=1);

namespace App\Http\News;

use BuzzingPixel\Scribble\ScribbleApiContract;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class GetNewsItemAction
{
    /** @var ScribbleApiContract */
    private $scribbleApi;
    /** @var GetNewsItemResponder */
    private $responder;

    public function __construct(
        ScribbleApiContract $scribbleApi,
        GetNewsItemResponder $responder
    ) {
        $this->scribbleApi = $scribbleApi;
        $this->responder   = $responder;
    }

    /**
     * @throws Throwable
     */
    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        $this->responder->setSlug((string) $request->getAttribute('slug'));

        $this->scribbleApi->getContentPathCollection('News', $this->responder);

        return $this->responder->createResponse();
    }
}
