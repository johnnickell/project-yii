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
    /** @param array<string, string> $parameters */
    public function __construct(private string $root, private array $parameters)
    {
    }

    public function getDefinitions(): array
    {
        $templatesPath = $this->root.'/'.$this->parameters['app.templates_path'];
        $view = new View();

        return [
            ...YiiCapabilityConfiguration::view($view, $templatesPath),
            Environment::class => fn (): Environment => new Environment(new FilesystemLoader($templatesPath)),
            TwigTemplateRenderer::class => TwigTemplateRenderer::class,
            WebView::class => fn (ContainerInterface $container): WebView => (new WebView($templatesPath))
                ->withRenderers(['twig' => $container->get(TwigTemplateRenderer::class)]),
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
