<?php

declare(strict_types=1);

use corbomite\di\Di;
use corbomite\http\Kernel;

require dirname(__DIR__) . '/config/bootstrap.php';

if (getenv('DEV_MODE') === 'true') {
    require dirname(__DIR__) . '/config/devMode.php';
}

/** @noinspection PhpUnhandledExceptionInspection */
// TODO: Add error handler argument for 404 and 500 error pages in production
Di::diContainer()->get(Kernel::class)();
