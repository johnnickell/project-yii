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

/**
 * Class PublicationProvider
 */
final readonly class PublicationProvider implements ServiceProviderInterface
{
    /**
     * Constructs PublicationProvider
     */
    public function __construct(private readonly ProviderContext $context)
    {
    }

    /**
     * Returns publication definitions
     *
     * @return array<string, mixed>
     */
    public function getDefinitions(): array
    {
        return [
            HubInterface::class     => $this->hub(...),
            Publisher::class        => MercureHubPublisher::class,
            PrivatePublisher::class => PrivateMercureHubPublisher::class
        ];
    }

    /**
     * Returns no publication extensions
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return [];
    }

    /**
     * Returns the configured publication hub or local fallback
     */
    private function hub(): HubInterface
    {
        $configured = $this->context->parameters['app.mercure_hub'] ?? null;

        return $configured instanceof HubInterface ? $configured : new MockHub(
            $this->context->parameters['app.mercure_url'],
            new StaticTokenProvider($this->context->parameters['app.mercure_token']),
            fn (): string => $this->context->parameters['app.publication_result']
        );
    }
}
