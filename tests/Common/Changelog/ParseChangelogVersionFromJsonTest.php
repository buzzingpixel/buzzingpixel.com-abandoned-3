<?php

declare(strict_types=1);

namespace Tests\Common\Changelog;

use App\Common\Changelog\ParseChangelogVersionFromJson;
use App\Common\Changelog\ParseChangelogVersionFromJsonHandler;
use MJErwin\ParseAChangelog\Reader;
use MJErwin\ParseAChangelog\Release;
use PHPUnit\Framework\TestCase;

class ParseChangelogVersionFromJsonTest extends TestCase
{
    /** @var ParseChangelogVersionFromJsonHandler */
    private $handler;

    /** @var ParseChangelogVersionFromJson */
    private $object;

    protected function setUp() : void
    {
        $this->handler = new class() implements ParseChangelogVersionFromJsonHandler
        {
            /** @var bool */
            public $hasFailed = false;

            public function parsingFailed() : void
            {
                $this->hasFailed = true;
            }

            /** @var Release|null */
            public $release;

            public function parsingSucceeded(Release $release) : void
            {
                $this->release = $release;
            }
        };

        $this->object = new ParseChangelogVersionFromJson();
    }

    public function testParseWhenFailsRead() : void
    {
        $this->object->parse('/foo', '1.2.3', $this->handler);

        self::assertSame(true, $this->handler->hasFailed);

        self::assertSame(null, $this->handler->release);
    }

    public function testParseWhenFailsVersion() : void
    {
        $loc = __DIR__ . '/TestChangelog.md';

        $this->object->parse($loc, '1.2.3', $this->handler);

        self::assertSame(true, $this->handler->hasFailed);

        self::assertSame(null, $this->handler->release);
    }

    public function testParse() : void
    {
        $loc = __DIR__ . '/TestChangelog.md';

        $version = '2.1.1';

        $this->object->parse($loc, $version, $this->handler);

        self::assertSame(false, $this->handler->hasFailed);

        $reader = new Reader($loc);

        $release = $reader->getReleases()[$version];

        self::assertEquals(
            $release,
            $this->handler->release
        );
    }
}
