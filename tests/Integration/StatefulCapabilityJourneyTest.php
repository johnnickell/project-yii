<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use Fight\Common\Application\FileStorage\FileStorage;
use Fight\Common\Application\Repository\TransactionalUnitOfWork;
use Fight\Common\Application\Scheduler\Scheduler;
use PHPUnit\Framework\TestCase;
use Yiisoft\Db\Connection\ConnectionInterface;

final class StatefulCapabilityJourneyTest extends TestCase
{
    public function test_transactions_commit_and_roll_back_at_the_booted_yii_connection(): void
    {
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer();
        $connection = $container->get(ConnectionInterface::class);
        $connection->createCommand('CREATE TABLE journey (value TEXT NOT NULL)')->execute();
        $unitOfWork = $container->get(TransactionalUnitOfWork::class);
        $unitOfWork->commitTransactional(static fn () => $connection->createCommand("INSERT INTO journey VALUES ('committed')")->execute());

        try {
            $unitOfWork->commitTransactional(static function () use ($connection): void {
                $connection->createCommand("INSERT INTO journey VALUES ('rolled-back')")->execute();
                throw new \RuntimeException('rollback');
            });
            self::fail('The transaction exception must escape.');
        } catch (\RuntimeException $exception) {
            self::assertSame('rollback', $exception->getMessage());
        }

        self::assertSame(['committed'], $connection->createCommand('SELECT value FROM journey')->queryColumn());
    }

    public function test_storage_and_scheduler_use_independent_configured_runtime_roots(): void
    {
        $base = sys_get_temp_dir().'/project-yii-state-'.bin2hex(random_bytes(6));
        $factory = new ConfiguredApplicationFactory(dirname(__DIR__, 2));
        $first = $factory->createContainer([
            'app.storage_path' => $base.'/first-storage',
            'app.scheduler_path' => $base.'/first-scheduler',
        ]);
        $second = $factory->createContainer([
            'app.storage_path' => $base.'/second-storage',
            'app.scheduler_path' => $base.'/second-scheduler',
        ]);

        $first->get(FileStorage::class)->putFile('journey.txt', 'first-root');
        self::assertSame('first-root', $first->get(FileStorage::class)->getFileContents('journey.txt'));
        self::assertFalse($second->get(FileStorage::class)->hasFile('journey.txt'));

        $ran = false;
        $first->get(Scheduler::class)->addJob('isolated-job', static fn (): bool => true, static function () use (&$ran): bool {
            $ran = true;

            return true;
        });
        $first->get(Scheduler::class)->run();
        self::assertTrue($ran);
        self::assertFileExists($base.'/first-scheduler/isolated-job.lock');
        self::assertFileDoesNotExist($base.'/second-scheduler/isolated-job.lock');
    }
}
