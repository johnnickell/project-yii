<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use App\Adapter\Http\HomeMiddleware;
use App\Web\HomeHandler;
use Fight\Common\Adapter\ServiceContainer\Yii\YiiCapabilityConfiguration;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Middleware\Dispatcher\MiddlewareDispatcher;
use Yiisoft\Middleware\Dispatcher\MiddlewareFactory;
use Yiisoft\Router\FastRoute\UrlGenerator as NativeUrlGenerator;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollector;
use Yiisoft\View\Twig\TwigTemplateRenderer;
use Yiisoft\View\View;
use Yiisoft\View\WebView;
use Yiisoft\Yii\Http\Application;

final readonly class ApplicationProvider implements ServiceProviderInterface
{
    /** @param array<string, string> $parameters */
    public function __construct(private string $root, private array $parameters)
    {
    }

    public function getDefinitions(): array
    {
        $collector = new RouteCollector();
        $collector->addRoute(Route::get('/')->name('home'));
        $view = new View();

        return [
            ...YiiCapabilityConfiguration::routing(new NativeUrlGenerator(new RouteCollection($collector))),
            ...YiiCapabilityConfiguration::view($view, $this->root.'/resources/views'),
            Environment::class => fn (): Environment => new Environment(new FilesystemLoader($this->root.'/resources/views')),
            TwigTemplateRenderer::class => TwigTemplateRenderer::class,
            WebView::class => fn (ContainerInterface $container): WebView => (new WebView($this->root.'/resources/views'))
                ->withRenderers(['twig' => $container->get(TwigTemplateRenderer::class)]),
            RequestHandlerInterface::class => HomeHandler::class,
            MiddlewareDispatcher::class => fn (ContainerInterface $container): MiddlewareDispatcher =>
                (new MiddlewareDispatcher(new MiddlewareFactory($container)))->withMiddlewares([HomeMiddleware::class]),
            Application::class => fn (ContainerInterface $container): Application =>
                new Application($container->get(MiddlewareDispatcher::class)),
            Response::class => Response::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
