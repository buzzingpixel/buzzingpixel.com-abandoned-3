<?php

declare(strict_types=1);

namespace App\Http\News;

use BuzzingPixel\Scribble\ScribbleApiContract;
use corbomite\http\exceptions\Http404Exception;
use Psr\Http\Message\ServerRequestInterface;
use function dd;
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
     * @throws Http404Exception
     */
    public function __invoke(ServerRequestInterface $request) : void
    {
        $page = $request->getAttribute('page');

        if ($page === '0' || $page === '1') {
            throw new Http404Exception();
        }

        $page = max(1, (int) $page);

        $this->scribbleApi->getContentPathCollection('News', $this->responder);

        dd($page);
    }
}
