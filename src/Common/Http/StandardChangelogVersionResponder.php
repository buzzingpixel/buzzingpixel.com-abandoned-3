<?php

declare(strict_types=1);

namespace App\Common\Http;

use App\Common\Changelog\ParseChangelogVersionFromJsonHandler;
use corbomite\http\exceptions\Http404Exception;
use corbomite\twig\TwigEnvironment;
use MJErwin\ParseAChangelog\Release;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class StandardChangelogVersionResponder implements ParseChangelogVersionFromJsonHandler
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

    public function parsingFailed() : void
    {
        $this->hasFailed = true;
    }

    /** @var Release|null */
    private $release;

    public function parsingSucceeded(Release $release) : void
    {
        $this->release = $release;
    }

    /**
     * @throws Http404Exception
     */
    public function createResponseBasedOnInput(
        string $base,
        string $metaTitle,
        string $titleAreasGlobal,
        string $navAreasGlobal,
        string $breadcrumbBaseTitle
    ) : ResponseInterface {
        if ($this->hasFailed || ! $this->release) {
            throw new Http404Exception();
        }

        $response = $this->responseFactory->createResponse(200)
            ->withHeader('Content-Type', 'text/html');

        try {
            $metaTitle = $this->release->getVersion() . ' | ' . $metaTitle;

            $response->getBody()->write(
                $this->twig->renderAndMinify('ChangelogVersion.twig', [
                    'release' => $this->release,
                    'metaTitle' => $metaTitle,
                    'titleAreasGlobal' => $titleAreasGlobal,
                    'navAreasGlobal' => $navAreasGlobal,
                    'activeHref' => $base,
                    'base' => $base,
                    'breadcrumbBaseTitle' => $breadcrumbBaseTitle,
                ])
            );
        } catch (Throwable $e) {
            throw new Http404Exception();
        }

        return $response;
    }
}
