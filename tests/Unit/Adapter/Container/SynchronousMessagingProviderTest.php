<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\SynchronousMessagingProvider;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Class SynchronousMessagingProviderTest
 */
#[CoversClass(SynchronousMessagingProvider::class)]
final class SynchronousMessagingProviderTest extends TestCase
{
    /**
     * Tests synchronous message bus selection
     */
    public function testItExposesTheSynchronousMessageBus(): void
    {
        $provider = new SynchronousMessagingProvider();
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());

        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 4))->createContainer(
            providerNames: ['synchronous-messaging-policy', 'fight-common-messaging']
        );
        self::assertInstanceOf(SynchronousCommandBus::class, $container->get(SynchronousCommandBus::class));
    }
}
