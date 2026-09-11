<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\MessengerFallbackProvider;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

#[CoversClass(MessengerFallbackProvider::class)]
final class MessengerFallbackProviderTest extends TestCase
{
    public function test_it_wires_async_buses_to_the_in_memory_transport(): void
    {
        $provider = new MessengerFallbackProvider();
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());

        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 4))
            ->createContainer(providerNames: ['messenger-fallback-policy']);
        self::assertInstanceOf(AsynchronousCommandBus::class, $container->get(AsynchronousCommandBus::class));
        self::assertInstanceOf(InMemoryTransport::class, $container->get(InMemoryTransport::class));
    }
}
