<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Cache\Psr6\Psr6Cache;
use Fight\Common\Application\Cache\Cache;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Yiisoft\Cache\ArrayCache as YiiArrayCache;
use Yiisoft\Di\ServiceProviderInterface;

final readonly class CacheProvider implements ServiceProviderInterface
{
    public function getDefinitions(): array
    {
        $pool = new ArrayAdapter();
        $logger = new NullLogger();

        return [
            CacheItemPoolInterface::class => $pool,
            Cache::class => new Psr6Cache($pool, $logger),
            YiiArrayCache::class => new YiiArrayCache(),
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}