<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\ServiceContainer\Yii\YiiCapabilityConfiguration;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\View\Twig\TwigTemplateRenderer;
use Yiisoft\View\View;
use Yiisoft\View\WebView;

final readonly class ViewProvider implements ServiceProviderInterface
{
    public function __construct(private ProviderContext $context)
    {
    }

    /** @return array<string, mixed> */
    public function getDefinitions(): array
    {
        $templatesPath = $this->context->root . '/' . $this->context->parameters['app.templates_path'];
        $view = new View();

        return [
            ...YiiCapabilityConfiguration::view($view, $templatesPath),
            Environment::class => fn (): Environment => new Environment(new FilesystemLoader($templatesPath)),
            TwigTemplateRenderer::class => TwigTemplateRenderer::class,
            WebView::class => fn (ContainerInterface $container): WebView => new WebView($templatesPath)
                ->withRenderers(['twig' => $container->get(TwigTemplateRenderer::class)]),
        ];
    }

    /** @return array<string, mixed> */
    public function getExtensions(): array
    {
        return [];
    }
}
