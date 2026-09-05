<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use App\Web\HomeHandler;
use Fight\Common\Adapter\ServiceContainer\Yii\YiiCapabilityConfiguration;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Middleware\Dispatcher\MiddlewareDispatcher;
use Yiisoft\Middleware\Dispatcher\MiddlewareFactory;
use Yiisoft\Router\FastRoute\UrlGenerator as NativeUrlGenerator;
use Yiisoft\Router\FastRoute\UrlMatcher as NativeUrlMatcher;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Router\Middleware\Router;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollector;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\View\Twig\TwigTemplateRenderer;
use Yiisoft\View\View;
use Yiisoft\View\WebView;
use Yiisoft\Yii\Http\Application;
use Yiisoft\Yii\Http\Handler\NotFoundHandler;

final readonly class ApplicationProvider implements ServiceProviderInterface
{
    /** @param array<string, string> $parameters */
    public function __construct(private string $root, private array $parameters)
    {
    }

    public function getDefinitions(): array
    {
        $templatesPath = $this->root.'/'.$this->parameters['app.templates_path'];
        $collector = new RouteCollector();
        $collector->addRoute(
            Route::get($this->parameters['app.route_path'])
                ->name($this->parameters['app.route_name'])
                ->action([HomeHandler::class, 'handle']),
        );
        $routes = new RouteCollection($collector);
        $view = new View();

        return [
            ...YiiCapabilityConfiguration::routing(new NativeUrlGenerator($routes)),
            ...YiiCapabilityConfiguration::view($view, $templatesPath),
            Environment::class => fn (): Environment => new Environment(new FilesystemLoader($templatesPath)),
            TwigTemplateRenderer::class => TwigTemplateRenderer::class,
            WebView::class => fn (ContainerInterface $container): WebView => (new WebView($templatesPath))
                ->withRenderers(['twig' => $container->get(TwigTemplateRenderer::class)]),
            HomeHandler::class => fn (WebView $view): HomeHandler => new HomeHandler(
                $view,
                $this->parameters['app.route_name'],
            ),
            ResponseFactoryInterface::class => Psr17Factory::class,
            UrlMatcherInterface::class => new NativeUrlMatcher($routes),
            CurrentRoute::class => CurrentRoute::class,
            MiddlewareDispatcher::class => fn (ContainerInterface $container): MiddlewareDispatcher =>
                (new MiddlewareDispatcher(new MiddlewareFactory($container)))->withMiddlewares([Router::class]),
            Application::class => fn (ContainerInterface $container): Application =>
                new Application(
                    $container->get(MiddlewareDispatcher::class),
                    null,
                    new NotFoundHandler($container->get(ResponseFactoryInterface::class)),
                ),
            Response::class => Response::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
