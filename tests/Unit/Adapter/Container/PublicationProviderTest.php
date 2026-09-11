<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\PublicationProvider;
use App\Adapter\Container\ProviderContext;
use App\Tests\Fixture\RecordingHub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\MockHub;

#[CoversClass(PublicationProvider::class)]
final class PublicationProviderTest extends TestCase
{
    public function test_it_uses_an_explicit_hub_or_builds_the_configured_mock(): void
    {
        $root = dirname(__DIR__, 4);
        $configuredHub = new RecordingHub();
        $explicit = new ConfiguredApplicationFactory($root)->createContainer(
            ['app.mercure_hub' => $configuredHub],
            ['publication-policy'],
        );
        self::assertSame($configuredHub, $explicit->get(HubInterface::class));

        $fallback = new ConfiguredApplicationFactory($root)->createContainer(providerNames: ['publication-policy']);
        self::assertInstanceOf(MockHub::class, $fallback->get(HubInterface::class));

        $provider = new PublicationProvider(new ProviderContext('/project', [
            'app.mercure_url' => 'https://example.test/.well-known/mercure',
            'app.mercure_token' => 'token',
            'app.publication_result' => 'published',
        ]));
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());
    }
}
