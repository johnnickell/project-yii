<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\View\Twig\TwigTemplateRenderer;
use Yiisoft\View\View;
use Yiisoft\View\ViewInterface;
use Yiisoft\View\WebView;

/**
 * Class ViewProvider
 */
final readonly class ViewProvider implements ServiceProviderInterface
{
    /**
     * Constructs ViewProvider
     */
    public function __construct(private ProviderContext $context)
    {
    }

    /**
     * Returns native view and Twig definitions at the configured template root
     *
     * @return array<string, mixed>
     */
    public function getDefinitions(): array
    {
        $templatesPath = $this->context->root.'/'.$this->context->parameters['app.templates_path'];
        $view = new View();

        return [
            ViewInterface::class        => $view,
            'fight.templates_path'      => static fn (): string => $templatesPath,
            Environment::class          => fn (): Environment => new Environment(new FilesystemLoader($templatesPath)),
            TwigTemplateRenderer::class => TwigTemplateRenderer::class,
            WebView::class              => fn (ContainerInterface $container): WebView => new WebView($templatesPath)
                ->withRenderers(['twig' => $container->get(TwigTemplateRenderer::class)])
        ];
    }

    /**
     * Returns no view extensions
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return [];
    }
}
