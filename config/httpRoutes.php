<?php

declare(strict_types=1);

use App\Http\HomePage\GetHomePage\HomePageAction;
use FastRoute\RouteCollector;

/**
 * @see https://github.com/nikic/FastRoute
 */

/** @var RouteCollector $r */

$r->get('/', HomePageAction::class);
