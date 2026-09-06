<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\EventSourcing\InMemory\InMemoryEventStore;
use Fight\Common\Adapter\ServiceContainer\Yii\YiiCapabilityConfiguration;
use Fight\Common\Domain\EventSourcing\EventMapper;
use Fight\Common\Domain\EventSourcing\EventStore;
use Psr\Log\NullLogger;
use Yiisoft\Cache\ArrayCache;
use Yiisoft\Db\Cache\SchemaCache;
use Yiisoft\Db\Sqlite\Connection;
use Yiisoft\Db\Sqlite\Driver;
use Yiisoft\Di\ServiceProviderInterface;

final readonly class PersistenceProvider implements ServiceProviderInterface
{
    public function __construct(ProviderContext $context)
    {
    }

    public function getDefinitions(): array
    {
        $cache = new ArrayCache();
        $schemaCache = new SchemaCache($cache);
        $connection = new Connection(new Driver('sqlite::memory:'), $schemaCache);

        return [
            ...YiiCapabilityConfiguration::persistence($connection, $cache, new NullLogger()),
            SchemaCache::class => $schemaCache,
            EventMapper::class => static fn (): EventMapper => new EventMapper([]),
            EventStore::class => InMemoryEventStore::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
