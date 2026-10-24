<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\CacheProvider;
use Fight\Common\Application\Cache\Cache;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

/**
 * Class CacheProviderTest
 */
#[CoversClass(CacheProvider::class)]
final class CacheProviderTest extends TestCase
{
    /**
     * Tests project and PSR cache behavior
     */
    public function testItExposesWorkingProjectAndPsrCaches(): void
    {
        $provider = new CacheProvider();
        self::assertSame([], $provider->getExtensions());
        self::assertNotEmpty($provider->getDefinitions());

        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 4))
            ->createContainer(providerNames: ['cache-policy']);
        self::assertTrue($container->get(CacheInterface::class)->set('key', 'value'));
        self::assertSame('value', $container->get(CacheInterface::class)->get('key'));
        self::assertSame(
            'loaded',
            $container->get(Cache::class)->read('fight-key', static fn (): string => 'loaded', 60)
        );
    }
}
