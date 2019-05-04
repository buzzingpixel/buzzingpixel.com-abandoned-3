<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\FileContent;

use BuzzingPixel\Scribble\ScribbleApi as ScribbleApiSource;
use BuzzingPixel\Scribble\ScribbleApiContract;
use BuzzingPixel\Scribble\Services\GetContentFromFile\GetContentFromFileDelegate;
use BuzzingPixel\Scribble\Services\GetContentFromPath\GetContentFromPathDelegate;
use BuzzingPixel\Scribble\Services\GetContentPathCollection\GetContentPathCollectionDelegate;
use const DIRECTORY_SEPARATOR;
use function ltrim;
use function rtrim;

class ScribbleApi implements ScribbleApiContract
{
    /** @var ScribbleApiSource */
    private $scribbleApi;
    /** @var string */
    private $baseContentDir;

    public function __construct(ScribbleApiSource $scribbleApi, string $baseContentDir)
    {
        $this->scribbleApi    = $scribbleApi;
        $this->baseContentDir = rtrim($baseContentDir, DIRECTORY_SEPARATOR);
    }

    public function getContentFromFile(
        string $filePath,
        GetContentFromFileDelegate $handler
    ) : void {
        $this->scribbleApi->getContentFromFile(
            $this->parsePath($filePath),
            $handler
        );
    }

    /**
     * @param string[] $extensions
     */
    public function getContentFromPath(
        string $dir,
        GetContentFromPathDelegate $handler,
        array $extensions = ['md']
    ) : void {
        $this->scribbleApi->getContentFromPath(
            $this->parsePath($dir),
            $handler,
            $extensions
        );
    }

    /**
     * @param string[] $extensions
     */
    public function getContentPathCollection(
        string $dir,
        GetContentPathCollectionDelegate $handler,
        array $extensions = ['md']
    ) : void {
        $this->scribbleApi->getContentPathCollection(
            $this->parsePath($dir),
            $handler,
            $extensions
        );
    }

    private function parsePath(string $path) : string
    {
        return $this->baseContentDir . DIRECTORY_SEPARATOR . ltrim(
            $path,
            DIRECTORY_SEPARATOR
        );
    }
}
