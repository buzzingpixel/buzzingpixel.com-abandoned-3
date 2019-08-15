<?php

declare(strict_types=1);

namespace App\Common\Http;

use App\Common\Changelog\ParseChangelogFromJsonHandler;
use App\Common\Pagination\Pagination;
use corbomite\http\exceptions\Http404Exception;
use corbomite\twig\TwigEnvironment;
use MJErwin\ParseAChangelog\Release;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use function array_slice;
use function count;

class StandardChangelogResponder implements ParseChangelogFromJsonHandler
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

    /** @var bool */
    private $hasFailed = false;

    /** @var Release[] */
    private $content = [];

    public function parsingFailed() : void
    {
        $this->hasFailed = true;
    }

    /**
     * @inheritDoc
     */
    public function parsingSucceeded(array $content) : void
    {
        $this->content = $content;
    }

    /**
     * @throws Http404Exception
     */
    public function createResponseBasedOnInput(
        int $limit,
        int $page,
        string $base,
        string $metaTitle,
        string $titleAreasGlobal,
        string $navAreasGlobal
    ) : ResponseInterface {
        if ($this->hasFailed) {
            throw new Http404Exception();
        }

        if ($page < 1) {
            throw new Http404Exception();
        }

        $pageZeroIndex = $page - 1;

        $pageContent = array_slice($this->content, $pageZeroIndex * $limit, $limit);

        if (! $pageContent) {
            throw new Http404Exception();
        }

        $response = $this->responseFactory->createResponse(200)
            ->withHeader('Content-Type', 'text/html');

        $pagination = (new Pagination())->withBase($base)
            ->withCurrentPage($page)
            ->withPerPage($limit)
            ->withTotalResults(count($this->content));

        try {
            $response->getBody()->write(
                $this->twig->renderAndMinify('ChangelogIndex.twig', [
                    'pageContent' => $pageContent,
                    'allContent' => $this->content,
                    'pagination' => $pagination,
                    'metaTitle' => $metaTitle,
                    'titleAreasGlobal' => $titleAreasGlobal,
                    'navAreasGlobal' => $navAreasGlobal,
                    'activeHref' => $base,
                ])
            );
        } catch (Throwable $e) {
            throw new Http404Exception();
        }

        return $response;
    }
}
