<?php

declare(strict_types=1);

use App\Http\HomePage\GetHomePage\HomePageAction;
use App\Http\News\GetNewsAction;
use App\Http\News\GetNewsItemAction;
use App\Http\Software\AnselCraft\GetAnselCraftAction;
use App\Http\Software\AnselCraft\GetAnselCraftChangelog;
use FastRoute\RouteCollector;

/**
 * @see https://github.com/nikic/FastRoute
 */

/** @var RouteCollector $r */

// Home Page
$r->get('/', HomePageAction::class);

// News Routes
$r->get('/news[/page/{page:\d+}]', GetNewsAction::class);
$r->get('/news/{slug}', GetNewsItemAction::class);

/**
 * Software Routes
 */

// Ansel Craft routes
$r->get('/software/ansel-craft', GetAnselCraftAction::class);
$r->get('/software/ansel-craft/changelog[/page/{page:\d+}]', GetAnselCraftChangelog::class);
