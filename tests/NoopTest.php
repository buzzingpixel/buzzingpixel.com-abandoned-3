<?php

declare(strict_types=1);

namespace tests;

use App\Noop;
use PHPUnit\Framework\TestCase;

class NoopTest extends TestCase
{
    public function testNoop() : void
    {
        self::assertNull((new Noop())());
    }
}
