<?php

declare(strict_types=1);

namespace App\Http\News;

use BuzzingPixel\Scribble\Services\GetContentFromFile\Content;
use BuzzingPixel\Scribble\Services\GetContentPathCollection\ContentPathCollection;
use BuzzingPixel\Scribble\Services\GetContentPathCollection\GetContentPathCollectionDelegate;
use corbomite\http\exceptions\Http404Exception;
use corbomite\twig\TwigEnvironment;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class GetNewsItemResponder implements GetContentPathCollectionDelegate
{
    /** @var ResponseFactoryInterface */
    private $responseFactory;
    /** @var TwigEnvironment */
    private $twig;

    /** @var string */
    private $slug;
    /** @var Content|null */
    private $entry;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        TwigEnvironment $twig
    ) {
        $this->responseFactory = $responseFactory;
        $this->twig            = $twig;
    }

    public function setSlug(string $slug) : void
    {
        $this->slug = $slug;
    }

    public function unableToParsePath() : void
    {
    }

    public function noResults() : void
    {
    }

    public function contentRetrieved(ContentPathCollection $collection) : void
    {
        foreach ($collection as $item) {
            $entry = $item->filterMetaEqualTo('slug', $this->slug);

            if (! $entry) {
                continue;
            }

            $this->entry = $entry->getItemAtIndex(0);

            break;
        }
    }

    /**
     * @throws Throwable
     */
    public function createResponse() : ResponseInterface
    {
        if (! $this->entry) {
            throw new Http404Exception();
        }

        $response = $this->responseFactory->createResponse(200)
            ->withHeader('Content-Type', 'text/html');

        $response->getBody()->write(
            $this->twig->renderAndMinify('NewsItem.twig', [
                'entry' => $this->entry,
            ])
        );

        return $response;
    }
}
