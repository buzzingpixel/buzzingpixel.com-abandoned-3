<?php

declare(strict_types=1);

namespace App\Common\Changelog;

use MJErwin\ParseAChangelog\Release;

interface ParseChangelogFromJsonHandler
{
    public function parsingFailed() : void;

    /**
     * @param Release[] $content
     */
    public function parsingSucceeded(array $content) : void;
}
