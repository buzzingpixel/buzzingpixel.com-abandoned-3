<?php

declare(strict_types=1);

use App\Http\Error\ErrorPageMiddleware;
use corbomite\di\Di;
use corbomite\http\Kernel;

require dirname(__DIR__) . '/config/bootstrap.php';

if (getenv('DEV_MODE') === 'true') {
    require dirname(__DIR__) . '/config/devMode.php';
}

/** @noinspection PhpUnhandledExceptionInspection */
Di::diContainer()->get(Kernel::class)(ErrorPageMiddleware::class);
