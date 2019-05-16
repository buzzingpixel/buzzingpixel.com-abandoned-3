<?php

declare(strict_types=1);

namespace Tests\Common\Infrastructure\FileContent;

use App\Common\Infrastructure\FileContent\ScribbleApi;
use BuzzingPixel\Scribble\ScribbleApi as ScribbleApiSource;
use BuzzingPixel\Scribble\Services\GetContentFromFile\GetContentFromFileDelegate;
use BuzzingPixel\Scribble\Services\GetContentFromPath\GetContentFromPathDelegate;
use BuzzingPixel\Scribble\Services\GetContentPathCollection\GetContentPathCollectionDelegate;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Throwable;
use const DIRECTORY_SEPARATOR;

class ScribbleApiTest extends TestCase
{
    /** @var string */
    private $baseDir;
    /** @var MockObject&ScribbleApiSource */
    private $scribbleApiSource;
    /** @var ScribbleApi */
    private $scribbleApi;

    /**
     * @throws Throwable
     */
    protected function setUp() : void
    {
        $this->baseDir = DIRECTORY_SEPARATOR . 'test' .
            DIRECTORY_SEPARATOR . 'base' .
            DIRECTORY_SEPARATOR . 'dir' .
            DIRECTORY_SEPARATOR;

        $this->scribbleApiSource = $this->createMock(ScribbleApiSource::class);

        $this->scribbleApi = new ScribbleApi(
            $this->scribbleApiSource,
            $this->baseDir
        );
    }

    /**
     * @throws Throwable
     */
    public function testGetContentFromFile() : void
    {
        /** @var MockObject&GetContentFromFileDelegate $handler */
        $handler = $this->createMock(GetContentFromFileDelegate::class);

        $this->scribbleApiSource->expects(self::once())
            ->method('getContentFromFile')
            ->with(
                self::equalTo($this->baseDir . 'testInputPath'),
                self::equalTo($handler)
            );

        $this->scribbleApi->getContentFromFile('/testInputPath', $handler);
    }

    /**
     * @throws Throwable
     */
    public function testGetContentFromPath() : void
    {
        /** @var MockObject&GetContentFromPathDelegate $handler */
        $handler = $this->createMock(GetContentFromPathDelegate::class);

        $this->scribbleApiSource->expects(self::once())
            ->method('getContentFromPath')
            ->with(
                self::equalTo($this->baseDir . 'testInputPath'),
                self::equalTo($handler),
                self::equalTo(['md', 'txt'])
            );

        $this->scribbleApi->getContentFromPath(
            '/testInputPath',
            $handler,
            ['md', 'txt']
        );
    }

    /**
     * @throws Throwable
     */
    public function testGetContentPathCollection() : void
    {
        /** @var MockObject&GetContentPathCollectionDelegate $handler */
        $handler = $this->createMock(GetContentPathCollectionDelegate::class);

        $this->scribbleApiSource->expects(self::once())
            ->method('getContentPathCollection')
            ->with(
                self::equalTo($this->baseDir . 'testInputPath'),
                self::equalTo($handler),
                self::equalTo(['md', 'txt'])
            );

        $this->scribbleApi->getContentPathCollection(
            '/testInputPath',
            $handler,
            ['md', 'txt']
        );
    }
}
