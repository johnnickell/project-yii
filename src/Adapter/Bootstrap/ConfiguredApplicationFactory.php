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
    /** @var array<string, true> */
    private const PROVIDERS_WITH_PROJECT_RUNTIME = [
        'routing-policy' => true,
        'view-policy' => true,
        'http-application' => true,
        'security-and-validation' => true,
        'synchronous-messaging-policy' => true,
        'messenger-fallback-policy' => true,
        'persistence-policy' => true,
        'files-policy' => true,
        'http-client-policy' => true,
        'operations-policy' => true,
        'mail-policy' => true,
        'sms-policy' => true,
        'publication-policy' => true,
        'observability-policy' => true,
    ];

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
        foreach ($providerMap as $providerName => $provider) {
            $providers[] = $this->createProvider($providerName, $provider, $parameters);
        }

        return new Container(ContainerConfig::create()->withProviders($providers));
    }

    /** @param array<string, mixed> $parameters */
    private function createProvider(string $providerName, string $provider, array $parameters): ServiceProviderInterface
    {
        if (isset(self::PROVIDERS_WITH_PROJECT_RUNTIME[$providerName])) {
            return new $provider($this->root, $parameters);
        }

        return new $provider();
    }

    /** @param array<string, mixed> $parameterOverrides */
    public function createApplication(array $parameterOverrides = []): Application
    {
        return $this->createContainer($parameterOverrides)->get(Application::class);
    }
}
