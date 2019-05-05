<?php

declare(strict_types=1);

namespace Tests\Common\Infrastructure\FileContent;

use App\Common\Infrastructure\FileContent\ScribbleApi;
use BuzzingPixel\Scribble\ScribbleApi as ScribbleApiSource;
use BuzzingPixel\Scribble\Services\GetContentFromFile\GetContentFromFileDelegate;
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
                self::equalTo(
                    $this->baseDir . 'testInputPath'
                ),
                self::equalTo($handler)
            );

        $this->scribbleApi->getContentFromFile('/testInputPath', $handler);
    }
}
