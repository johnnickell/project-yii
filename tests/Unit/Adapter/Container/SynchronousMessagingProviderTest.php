<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\SynchronousMessagingProvider;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SynchronousMessagingProvider::class)]
final class SynchronousMessagingProviderTest extends TestCase
{
    public function test_it_exposes_the_synchronous_message_bus(): void
    {
        $provider = new SynchronousMessagingProvider();
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());

        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 4))->createContainer(
            providerNames: ['synchronous-messaging-policy', 'fight-common-messaging'],
        );
        self::assertInstanceOf(SynchronousCommandBus::class, $container->get(SynchronousCommandBus::class));
    }
}
