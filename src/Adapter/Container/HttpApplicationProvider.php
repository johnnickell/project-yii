<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use App\Adapter\Http\HomeHandler;
use Nyholm\Psr7\Factory\Psr17Factory;
use Fight\Common\Adapter\Http\Psr17\JSendResponseFactory;
use Fight\Common\Adapter\Middleware\Psr15\JSendErrorMiddleware;
use Fight\Common\Adapter\Middleware\Psr15\JsonRequestMiddleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
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
    public function __construct(private ProviderContext $context)
    {
    }

    /** @return array<string, mixed> */
    public function getDefinitions(): array
    {
        return [
            HomeHandler::class => fn (
                WebView $view,
                ResponseFactoryInterface $responseFactory,
                StreamFactoryInterface $streamFactory,
            ): HomeHandler => new HomeHandler(
                $view,
                $this->context->parameters['app.route_name'],
                $responseFactory,
                $streamFactory,
            ),
            Psr17Factory::class => Psr17Factory::class,
            ResponseFactoryInterface::class => fn (Psr17Factory $factory): ResponseFactoryInterface => $factory,
            StreamFactoryInterface::class => fn (Psr17Factory $factory): StreamFactoryInterface => $factory,
            JSendResponseFactory::class => JSendResponseFactory::class,
            JsonRequestMiddleware::class => JsonRequestMiddleware::class,
            JSendErrorMiddleware::class => JSendErrorMiddleware::class,
            UrlMatcherInterface::class => fn (RouteCollection $routes): UrlMatcherInterface => new NativeUrlMatcher($routes),
            CurrentRoute::class => CurrentRoute::class,
            MiddlewareDispatcher::class => fn (ContainerInterface $container): MiddlewareDispatcher =>
                new MiddlewareDispatcher(new MiddlewareFactory($container))->withMiddlewares([
                    JSendErrorMiddleware::class,
                    JsonRequestMiddleware::class,
                    Router::class,
                ]),
            Application::class => fn (ContainerInterface $container): Application =>
                new Application(
                    $container->get(MiddlewareDispatcher::class),
                    null,
                    new NotFoundHandler($container->get(ResponseFactoryInterface::class)),
                ),
        ];
    }

    /** @return array<string, mixed> */
    public function getExtensions(): array
    {
        return [];
    }
}
