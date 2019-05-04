<?php

declare(strict_types=1);

namespace App\Common\Http;

use BuzzingPixel\Scribble\Services\GetContentFromPath\ContentCollection;
use BuzzingPixel\Scribble\Services\GetContentFromPath\GetContentFromPathDelegate;
use corbomite\twig\TwigEnvironment;
use LogicException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class StandardPageResponder implements GetContentFromPathDelegate
{
    /** @var ResponseFactoryInterface */
    private $responseFactory;
    /** @var TwigEnvironment */
    private $twig;

    /** @var ContentCollection|null */
    private $contentCollection;

    public function __construct(
        ResponseFactoryInterface $responseFactory,
        TwigEnvironment $twig
    ) {
        $this->responseFactory = $responseFactory;
        $this->twig            = $twig;
    }

    public function unableToParsePath() : void
    {
        throw new LogicException('Unable to find content');
    }

    public function noResults() : void
    {
        throw new LogicException('Unable to find content');
    }

    public function contentRetrieved(ContentCollection $collection) : void
    {
        $this->contentCollection = $collection;
    }

    /**
     * @throws Throwable
     */
    public function createResponseBasedOnInput() : ResponseInterface
    {
        if (! $this->contentCollection) {
            $response = $this->responseFactory->createResponse(404)
                ->withHeader('Content-Type', 'text/html');

            $response->getBody()->write(
                $this->twig->renderAndMinify('404.twig')
            );

            return $response;
        }

        $index = $this->contentCollection->filterMetaEqualTo(
            'baseNameNoExtension',
            'index'
        );

        if ($index) {
            $index = $index->first();
        }

        $contentCollection = $this->contentCollection->filterMetaNotEqualTo(
            'baseNameNoExtension',
            'index'
        );

        $response = $this->responseFactory->createResponse(200)
            ->withHeader('Content-Type', 'text/html');

        $response->getBody()->write(
            $this->twig->renderAndMinify('StandardPage.twig', [
                'index' => $index,
                'contentCollection' => $contentCollection,
            ])
        );

        return $response;
    }
}
