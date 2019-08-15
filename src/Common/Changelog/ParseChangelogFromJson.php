<?php

declare(strict_types=1);

namespace App\Common\Changelog;

use MJErwin\ParseAChangelog\Reader;
use Throwable;
use function array_values;

class ParseChangelogFromJson
{
    public function parse(string $jsonLoc, ParseChangelogFromJsonHandler $handler) : void
    {
        try {
            $this->innerParse($jsonLoc, $handler);
        } catch (Throwable $e) {
            $handler->parsingFailed();
        }
    }

    private function innerParse(string $jsonLoc, ParseChangelogFromJsonHandler $handler) : void
    {
        $reader = new Reader($jsonLoc);

        $handler->parsingSucceeded(array_values($reader->getReleases()));
    }
}
