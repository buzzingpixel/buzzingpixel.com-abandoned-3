<?php

declare(strict_types=1);

namespace App\Common\Changelog;

use MJErwin\ParseAChangelog\Reader;
use Throwable;

class ParseChangelogVersionFromJson
{
    public function parse(
        string $jsonLoc,
        string $version,
        ParseChangelogVersionFromJsonHandler $handler
    ) : void {
        try {
            $this->innerParse($jsonLoc, $version, $handler);
        } catch (Throwable $e) {
            $handler->parsingFailed();
        }
    }

    private function innerParse(
        string $jsonLoc,
        string $version,
        ParseChangelogVersionFromJsonHandler $handler
    ) : void {
        $reader = new Reader($jsonLoc);

        $release = $reader->getReleases()[$version];

        $handler->parsingSucceeded($release);
    }
}
