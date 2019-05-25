<?php

declare(strict_types=1);

namespace App\Http\News;

use BuzzingPixel\Scribble\Services\GetContentPathCollection\ContentPathCollection;
use BuzzingPixel\Scribble\Services\GetContentPathCollection\GetContentPathCollectionDelegate;
use function dd;

class GetNewsResponder implements GetContentPathCollectionDelegate
{
    public function unableToParsePath() : void
    {
    }

    public function noResults() : void
    {
    }

    public function contentRetrieved(ContentPathCollection $collection) : void
    {
        // TODO: Implement contentRetrieved() method.
        dd($collection);
    }
}
