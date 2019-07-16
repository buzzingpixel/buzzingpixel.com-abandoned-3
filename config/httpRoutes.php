<?php

declare(strict_types=1);

use App\Http\HomePage\GetHomePage\HomePageAction;
use App\Http\News\GetNewsAction;
use App\Http\News\GetNewsItemAction;
use FastRoute\RouteCollector;

/**
 * @see https://github.com/nikic/FastRoute
 */

/** @var RouteCollector $r */

$r->get('/', HomePageAction::class);

$r->get('/news[/page/{page:\d+}]', GetNewsAction::class);
$r->get('/news/{slug}', GetNewsItemAction::class);
