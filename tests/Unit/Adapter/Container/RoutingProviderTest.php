<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\ProviderContext;
use App\Adapter\Container\RoutingProvider;
use Fight\Common\Application\Routing\UrlGenerator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Class RoutingProviderTest
 */
#[CoversClass(RoutingProvider::class)]
final class RoutingProviderTest extends TestCase
{
    /**
     * Tests configured named-route collection
     */
    public function testItCollectsTheConfiguredNamedRoute(): void
    {
        $root = dirname(__DIR__, 4);
        $provider = new RoutingProvider(new ProviderContext($root, require $root.'/config/project-runtime.php'));
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());

        $container = new ConfiguredApplicationFactory($root)
            ->createContainer(providerNames: ['routing-policy', 'fight-common-routing']);
        self::assertSame('/', $container->get(UrlGenerator::class)->generate('home'));
    }
}
