<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use App\Adapter\Http\HomeHandler;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Middleware\Dispatcher\MiddlewareDispatcher;
use Yiisoft\Middleware\Dispatcher\MiddlewareFactory;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\FastRoute\UrlMatcher as NativeUrlMatcher;
use Yiisoft\Router\Middleware\Router;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\Http\Application;
use Yiisoft\Yii\Http\Handler\NotFoundHandler;

final readonly class HttpApplicationProvider implements ServiceProviderInterface
{
    /** @param array<string, string> $parameters */
    public function __construct(string $root, private array $parameters)
    {
    }

    public function getDefinitions(): array
    {
        return [
            HomeHandler::class => fn (WebView $view): HomeHandler => new HomeHandler(
                $view,
                $this->parameters['app.route_name'],
            ),
            ResponseFactoryInterface::class => Psr17Factory::class,
            UrlMatcherInterface::class => fn (RouteCollection $routes): UrlMatcherInterface => new NativeUrlMatcher($routes),
            CurrentRoute::class => CurrentRoute::class,
            MiddlewareDispatcher::class => fn (ContainerInterface $container): MiddlewareDispatcher =>
                (new MiddlewareDispatcher(new MiddlewareFactory($container)))->withMiddlewares([Router::class]),
            Application::class => fn (ContainerInterface $container): Application =>
                new Application(
                    $container->get(MiddlewareDispatcher::class),
                    null,
                    new NotFoundHandler($container->get(ResponseFactoryInterface::class)),
                ),
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
