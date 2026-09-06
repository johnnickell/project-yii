<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Socket\MercureHubPublisher;
use Fight\Common\Adapter\Socket\PrivateMercureHubPublisher;
use Fight\Common\Application\Socket\PrivatePublisher;
use Fight\Common\Application\Socket\Publisher;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Jwt\StaticTokenProvider;
use Symfony\Component\Mercure\MockHub;
use Yiisoft\Di\ServiceProviderInterface;

final class PublicationProvider implements ServiceProviderInterface
{
    /** @param array<string, mixed> $parameters */
    public function __construct(string $root, private readonly array $parameters)
    {
    }

    public function getDefinitions(): array
    {
        return [
            HubInterface::class => fn (): HubInterface => $this->hub(),
            Publisher::class => MercureHubPublisher::class,
            PrivatePublisher::class => PrivateMercureHubPublisher::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }

    private function hub(): HubInterface
    {
        $configured = $this->parameters['app.mercure_hub'] ?? null;

        return $configured instanceof HubInterface ? $configured : new MockHub(
            $this->parameters['app.mercure_url'],
            new StaticTokenProvider($this->parameters['app.mercure_token']),
            fn (): string => $this->parameters['app.publication_result'],
        );
    }
}
