<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\ObservabilityProvider;
use App\Adapter\Container\ProviderContext;
use Fight\Common\Application\Observability\HealthAggregator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class ObservabilityProviderTest
 */
#[CoversClass(ObservabilityProvider::class)]
final class ObservabilityProviderTest extends TestCase
{
    /**
     * Tests selected logging and health contracts
     */
    public function testItExposesTheSharedLoggerAndHealthContracts(): void
    {
        $provider = new ObservabilityProvider(new ProviderContext('/project', ['app.log_targets' => []]));
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());

        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 4))
            ->createContainer(providerNames: ['observability-policy']);
        self::assertInstanceOf(LoggerInterface::class, $container->get(LoggerInterface::class));
        self::assertInstanceOf(HealthAggregator::class, $container->get(HealthAggregator::class));
    }
}
