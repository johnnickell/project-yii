<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\ServiceContainer\Yii\YiiCapabilityConfiguration;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\FastRoute\UrlGenerator as NativeUrlGenerator;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollector;

final readonly class RoutingProvider implements ServiceProviderInterface
{
    public function __construct(private ProviderContext $context)
    {
    }

    public function getDefinitions(): array
    {
        $collector = new RouteCollector();
        $collector->addRoute(
            Route::methods($this->context->parameters['app.route_methods'], $this->context->parameters['app.route_path'])
                ->name($this->context->parameters['app.route_name'])
                ->action($this->context->parameters['app.route_action']),
        );
        $routes = new RouteCollection($collector);

        return [
            RouteCollection::class => $routes,
            ...YiiCapabilityConfiguration::routing(new NativeUrlGenerator($routes)),
            CurrentRoute::class => CurrentRoute::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
