<?php

declare(strict_types=1);

namespace App\Adapter\Bootstrap;

use App\Adapter\Container\ProviderContext;
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

    /**
     * @param array<string, mixed> $parameterOverrides
     * @param list<string> $providerNames
     */
    public function createContainer(array $parameterOverrides = [], array $providerNames = []): Container
    {
        $config = new Config(new ConfigPaths($this->root, 'config'), null, [], null);
        $parameters = array_replace($config->get('project-runtime'), $parameterOverrides);
        $providerMap = $config->get('providers');
        if ($providerNames !== []) {
            $unknownProviderNames = array_values(array_diff($providerNames, array_keys($providerMap)));
            if ($unknownProviderNames !== []) {
                throw new \InvalidArgumentException(sprintf(
                    'Unknown configured provider name(s): %s',
                    implode(', ', $unknownProviderNames),
                ));
            }

            $providerMap = array_intersect_key($providerMap, array_flip($providerNames));
        }
        $providers = [];
        foreach ($providerMap as $provider) {
            $providers[] = $this->createProvider($provider, $parameters);
        }

        return new Container(ContainerConfig::create()->withProviders($providers));
    }

    /** @param array<string, mixed> $parameters */
    private function createProvider(string|array $provider, array $parameters): ServiceProviderInterface
    {
        $className = is_array($provider) ? $provider['class'] : $provider;
        $needsRuntime = is_array($provider) && ($provider['runtime'] ?? false);

        if ($needsRuntime) {
            return new $className(new ProviderContext($this->root, $parameters));
        }

        return new $className();
    }

    /** @param array<string, mixed> $parameterOverrides */
    public function createApplication(array $parameterOverrides = []): Application
    {
        return $this->createContainer($parameterOverrides)->get(Application::class);
    }
}
