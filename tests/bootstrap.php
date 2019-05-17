<?php

declare(strict_types=1);

ini_set('display_errors', 'On');
ini_set('html_errors', '0');
error_reporting(-1);

session_start();

define('TESTS_BASE_PATH', __DIR__);

define('TESTING_APP_PATH', dirname(__DIR__));

define('APP_BASE_PATH', TESTING_APP_PATH);

putenv('CORBOMITE_DI_USE_AUTO_WIRING=true');

putenv('CORBOMITE_DI_USE_ANNOTATIONS=false');

require dirname(__DIR__) . '/vendor/autoload.php';
