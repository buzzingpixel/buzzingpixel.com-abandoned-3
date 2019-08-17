<?php

declare(strict_types=1);

namespace Tests\App\Common\Changelog;

use App\Common\Changelog\ParseChangelogFromJson;
use App\Common\Changelog\ParseChangelogFromJsonHandler;
use MJErwin\ParseAChangelog\Reader;
use MJErwin\ParseAChangelog\Release;
use PHPUnit\Framework\TestCase;
use function array_values;

class ParseChangelogFromJsonTest extends TestCase
{
    /** @var ParseChangelogFromJsonHandler */
    private $handler;

    /** @var ParseChangelogFromJson */
    private $object;

    protected function setUp() : void
    {
        $this->handler = new class() implements ParseChangelogFromJsonHandler
        {
            /** @var bool */
            public $hasFailed = false;

            /** @var Release[] */
            public $content = [];

            public function parsingFailed() : void
            {
                $this->hasFailed = true;
            }

            /**
             * @param Release[] $content
             */
            public function parsingSucceeded(array $content) : void
            {
                $this->content = $content;
            }
        };

        $this->object = new ParseChangelogFromJson();
    }

    public function testParseWhenFails() : void
    {
        $this->object->parse('/foo', $this->handler);

        self::assertSame(true, $this->handler->hasFailed);

        self::assertSame([], $this->handler->content);
    }

    public function testParse() : void
    {
        $loc = __DIR__ . '/TestChangelog.md';

        $this->object->parse($loc, $this->handler);

        self::assertSame(false, $this->handler->hasFailed);

        $reader = new Reader($loc);

        self::assertEquals(
            array_values($reader->getReleases()),
            $this->handler->content
        );
    }
}
