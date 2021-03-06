<?php

declare(strict_types=1);

namespace App\Http\News;

use App\Common\Pagination\Pagination;
use BuzzingPixel\Scribble\Services\GetContentPathCollection\ContentPathCollection;
use BuzzingPixel\Scribble\Services\GetContentPathCollection\GetContentPathCollectionDelegate;
use corbomite\http\exceptions\Http404Exception;
use corbomite\twig\TwigEnvironment;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class GetNewsResponder implements GetContentPathCollectionDelegate
{
    /** @var ResponseFactoryInterface */
    private $responseFactory;
    /** @var TwigEnvironment */
    private $twig;

    /** @var ContentPathCollection|null */
    private $collection;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        TwigEnvironment $twig
    ) {
        $this->responseFactory = $responseFactory;
        $this->twig            = $twig;
    }

    public function unableToParsePath() : void
    {
    }

    public function noResults() : void
    {
    }

    public function contentRetrieved(ContentPathCollection $collection) : void
    {
        $this->collection = $collection->reverseSortOrder();
    }

    /**
     * @throws Throwable
     */
    public function createResponse(int $limit, int $page) : ResponseInterface
    {
        if (! $this->collection) {
            throw new Http404Exception();
        }

        if ($page < 1) {
            throw new Http404Exception();
        }

        $pageZeroIndex = $page - 1;

        $collection = $this->collection->subSet($limit, $pageZeroIndex * $limit);

        if (! $collection) {
            throw new Http404Exception();
        }

        $response = $this->responseFactory->createResponse(200)
            ->withHeader('Content-Type', 'text/html');

        $pagination = (new Pagination())->withBase('news')
            ->withCurrentPage($page)
            ->withPerPage($limit)
            ->withTotalResults($this->collection->count());

        $response->getBody()->write(
            $this->twig->renderAndMinify('NewsIndex.twig', [
                'collection' => $collection,
                'pagination' => $pagination,
            ])
        );

        return $response;
    }
}
