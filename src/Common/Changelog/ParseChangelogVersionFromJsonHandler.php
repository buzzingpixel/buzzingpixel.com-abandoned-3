<?php

declare(strict_types=1);

namespace App\Common\Changelog;

use MJErwin\ParseAChangelog\Release;

interface ParseChangelogVersionFromJsonHandler
{
    public function parsingFailed() : void;

    public function parsingSucceeded(Release $release) : void;
}
