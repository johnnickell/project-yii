<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Cache\Psr6\Psr6Cache;
use Fight\Common\Application\Cache\Cache;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Yiisoft\Cache\ArrayCache as YiiArrayCache;
use Yiisoft\Di\ServiceProviderInterface;

/**
 * Class CacheProvider
 */
final readonly class CacheProvider implements ServiceProviderInterface
{
    /**
     * Returns cache definitions
     *
     * @return array<string, mixed>
     */
    public function getDefinitions(): array
    {
        $pool = new ArrayAdapter();
        $logger = new NullLogger();

        $yiiCache = new YiiArrayCache();

        return [
            CacheItemPoolInterface::class => $pool,
            Psr6Cache::class              => new Psr6Cache($pool, $logger),
            Cache::class                  => static fn (Psr6Cache $cache): Cache => $cache,
            YiiArrayCache::class          => $yiiCache,
            CacheInterface::class         => $yiiCache
        ];
    }

    /**
     * Returns no cache extensions
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return [];
    }
}
