<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\PersistenceProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Yiisoft\Db\Connection\ConnectionInterface;

#[CoversClass(PersistenceProvider::class)]
final class PersistenceProviderTest extends TestCase
{
    public function test_it_builds_a_logged_in_memory_sqlite_connection(): void
    {
        $provider = new PersistenceProvider();
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());

        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 4))->createContainer(
            providerNames: ['cache-policy', 'observability-policy', 'persistence-policy'],
        );
        $connection = $container->get(ConnectionInterface::class);
        self::assertSame('1', $connection->createCommand('SELECT 1')->queryScalar());
    }
}
