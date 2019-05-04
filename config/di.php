<?php

declare(strict_types=1);

use App\Common\Infrastructure\FileContent\ScribbleApi;
use BuzzingPixel\Scribble\ScribbleApiContract;
use Psr\Http\Message\ResponseFactoryInterface;
use Zend\Diactoros\ResponseFactory;
use function DI\autowire;
use function DI\get;

return [
    ResponseFactoryInterface::class => autowire(ResponseFactory::class),
    ScribbleApi::class => autowire()
        ->constructorParameter(
            'baseContentDir',
            APP_BASE_PATH . '/content'
        ),
    ScribbleApiContract::class => get(ScribbleApi::class),
];
