<?php

declare(strict_types=1);

namespace App\Http\News;

use BuzzingPixel\Scribble\ScribbleApiContract;
use corbomite\http\exceptions\Http404Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use function max;

class GetNewsAction
{
    /** @var ScribbleApiContract */
    private $scribbleApi;
    /** @var GetNewsResponder */
    private $responder;

    public function __construct(
        ScribbleApiContract $scribbleApi,
        GetNewsResponder $responder
    ) {
        $this->scribbleApi = $scribbleApi;
        $this->responder   = $responder;
    }

    /**
     * @throws Throwable
     */
    public function __invoke(ServerRequestInterface $request) : ResponseInterface
    {
        $page = (string) $request->getAttribute('page');

        if ($page === '0' || $page === '1') {
            throw new Http404Exception();
        }

        $page = max(1, (int) $page);

        $this->scribbleApi->getContentPathCollection('News', $this->responder);

        return $this->responder->createResponse(10, $page);
    }
}
