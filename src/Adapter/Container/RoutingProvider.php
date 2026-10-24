<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\FastRoute\UrlGenerator as NativeUrlGenerator;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollector;
use Yiisoft\Router\UrlGeneratorInterface;

/**
 * Class RoutingProvider
 */
final readonly class RoutingProvider implements ServiceProviderInterface
{
    /**
     * Constructs RoutingProvider
     */
    public function __construct(private ProviderContext $context)
    {
    }

    /**
     * Returns configured native route definitions
     *
     * @return array<string, mixed>
     */
    public function getDefinitions(): array
    {
        $collector = new RouteCollector();
        $collector->addRoute(
            Route::methods(
                $this->context->parameters['app.route_methods'],
                $this->context->parameters['app.route_path']
            )
                ->name($this->context->parameters['app.route_name'])
                ->action($this->context->parameters['app.route_action'])
        );
        $routes = new RouteCollection($collector);

        return [
            RouteCollection::class       => $routes,
            UrlGeneratorInterface::class => new NativeUrlGenerator($routes),
            CurrentRoute::class          => CurrentRoute::class
        ];
    }

    /**
     * Returns no routing extensions
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return [];
    }
}
