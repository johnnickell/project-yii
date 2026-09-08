<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\ServiceContainer\Yii\YiiCapabilityConfiguration;
use Psr\Log\NullLogger;
use Yiisoft\Cache\ArrayCache;
use Yiisoft\Db\Cache\SchemaCache;
use Yiisoft\Db\Sqlite\Connection;
use Yiisoft\Db\Sqlite\Driver;
use Yiisoft\Di\ServiceProviderInterface;

final readonly class PersistenceProvider implements ServiceProviderInterface
{
    public function getDefinitions(): array
    {
        $cache = new ArrayCache();
        $schemaCache = new SchemaCache($cache);
        $connection = new Connection(new Driver('sqlite::memory:'), $schemaCache);

        return [
            ...YiiCapabilityConfiguration::persistence($connection, $cache, new NullLogger()),
            SchemaCache::class => $schemaCache,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
