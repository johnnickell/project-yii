<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\OperationsProvider;
use App\Adapter\Container\ProviderContext;
use Fight\Common\Application\Scheduler\Scheduler;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class OperationsProviderTest
 */
#[CoversClass(OperationsProvider::class)]
final class OperationsProviderTest extends TestCase
{
    /**
     * Tests scheduler directory creation and reuse
     */
    public function testItCreatesAndReusesTheSchedulerRuntimeDirectory(): void
    {
        $path = sys_get_temp_dir().'/project-yii-operations-'.bin2hex(random_bytes(5));
        $provider = new OperationsProvider(new ProviderContext('/project', ['app.scheduler_path' => $path]));
        self::assertNotEmpty($provider->getDefinitions());
        self::assertDirectoryExists($path);
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());

        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 4))->createContainer(
            ['app.scheduler_path' => $path],
            ['operations-policy']
        );
        $ran = false;
        $scheduler = $container->get(Scheduler::class);
        $scheduler->addJob('configured-job', static fn (): bool => true, static function () use (&$ran): bool {
            $ran = true;

            return true;
        });
        $scheduler->run();
        self::assertTrue($ran);
        self::assertFileExists($path.'/configured-job.lock');
        unlink($path.'/configured-job.lock');
        rmdir($path);
    }

    /**
     * Tests rejection of an unusable scheduler directory
     */
    public function testItFailsWhenTheSchedulerDirectoryCannotBeCreated(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'project-yii-operations-file-');
        self::assertNotFalse($file);
        $provider = new OperationsProvider(new ProviderContext('/project', ['app.scheduler_path' => $file.'/nested']));

        $warning = null;
        set_error_handler(static function (int $severity, string $message) use (&$warning): bool {
            $warning = [$severity, $message];

            return true;
        });

        try {
            $provider->getDefinitions();
            self::fail('An unusable scheduler path must fail closed.');
        } catch (RuntimeException $exception) {
            self::assertStringContainsString('Could not create scheduler runtime path', $exception->getMessage());
        } finally {
            restore_error_handler();
        }

        self::assertNotNull($warning);
        self::assertSame(E_WARNING, $warning[0]);
        self::assertStringContainsString('mkdir()', $warning[1]);
    }
}
