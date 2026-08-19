<?php

declare(strict_types=1);

namespace App\Infrastructure\Container;

use App\Web\HomeHandler;
use Nyholm\Psr7\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Middleware\Dispatcher\MiddlewareDispatcher;
use Yiisoft\Middleware\Dispatcher\MiddlewareFactory;
use Yiisoft\View\Twig\TwigTemplateRenderer;
use Yiisoft\View\WebView;
use Yiisoft\Yii\Http\Application;

final readonly class ApplicationProvider implements ServiceProviderInterface
{
    public function __construct(private string $root)
    {
    }

    public function getDefinitions(): array
    {
        return [
            Environment::class => fn (): Environment => new Environment(
                new FilesystemLoader($this->root . '/resources/views')
            ),
            TwigTemplateRenderer::class => TwigTemplateRenderer::class,
            WebView::class => fn (ContainerInterface $container): WebView => (new WebView(
                $this->root . '/resources/views'
            ))->withRenderers(['twig' => $container->get(TwigTemplateRenderer::class)]),
            RequestHandlerInterface::class => HomeHandler::class,
            MiddlewareDispatcher::class => fn (ContainerInterface $container): MiddlewareDispatcher => (
                new MiddlewareDispatcher(new MiddlewareFactory($container))
            )->withMiddlewares([HomeHandler::class]),
            Application::class => fn (ContainerInterface $container): Application => new Application(
                $container->get(MiddlewareDispatcher::class)
            ),
            Response::class => Response::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
