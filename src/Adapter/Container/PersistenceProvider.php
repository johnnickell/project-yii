<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Yiisoft\Db\Cache\SchemaCache;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Sqlite\Connection;
use Yiisoft\Db\Sqlite\Driver;
use Yiisoft\Di\ServiceProviderInterface;

/**
 * Class PersistenceProvider
 */
final readonly class PersistenceProvider implements ServiceProviderInterface
{
    /**
     * Returns persistence definitions using selected cache and logging policy
     *
     * @return array<string, mixed>
     */
    public function getDefinitions(): array
    {
        return [
            SchemaCache::class         => static fn (CacheInterface $cache): SchemaCache => new SchemaCache($cache),
            ConnectionInterface::class => static function (
                SchemaCache $schemaCache,
                LoggerInterface $logger
            ): ConnectionInterface {
                $connection = new Connection(new Driver('sqlite::memory:'), $schemaCache);
                $connection->setLogger($logger);

                return $connection;
            }
        ];
    }

    /**
     * Returns no persistence extensions
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return [];
    }
}
