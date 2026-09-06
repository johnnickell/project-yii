<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use App\Adapter\Http\HomeHandler;
use Fight\Common\Adapter\ServiceContainer\Yii\YiiCapabilityConfiguration;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\FastRoute\UrlGenerator as NativeUrlGenerator;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollector;

final readonly class RoutingProvider implements ServiceProviderInterface
{
    /** @param array<string, string> $parameters */
    public function __construct(string $root, private array $parameters)
    {
    }

    public function getDefinitions(): array
    {
        $collector = new RouteCollector();
        $collector->addRoute(
            Route::get($this->parameters['app.route_path'])
                ->name($this->parameters['app.route_name'])
                ->action([HomeHandler::class, 'handle']),
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
