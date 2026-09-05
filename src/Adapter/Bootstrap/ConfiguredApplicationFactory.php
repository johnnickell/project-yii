<?php

declare(strict_types=1);

namespace App\Adapter\Bootstrap;

use Yiisoft\Config\Config;
use Yiisoft\Config\ConfigPaths;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Yii\Http\Application;

final readonly class ConfiguredApplicationFactory
{
    public function __construct(private string $root)
    {
    }

    /** @param array<string, mixed> $parameterOverrides */
    public function createContainer(array $parameterOverrides = []): Container
    {
        $config = new Config(new ConfigPaths($this->root, 'config'), null, [], null);
        $parameters = array_replace($config->get('project-runtime'), $parameterOverrides);
        $providers = array_map(
            fn (string $provider): ServiceProviderInterface => str_starts_with($provider, 'App\\')
                ? new $provider($this->root, $parameters)
                : new $provider(),
            array_values($config->get('providers')),
        );

        return new Container(ContainerConfig::create()->withProviders($providers));
    }

    /** @param array<string, mixed> $parameterOverrides */
    public function createApplication(array $parameterOverrides = []): Application
    {
        return $this->createContainer($parameterOverrides)->get(Application::class);
    }
}
