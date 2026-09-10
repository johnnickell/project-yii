<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Container\OperationsProvider;
use App\Adapter\Container\ProviderContext;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(OperationsProvider::class)]
final class OperationsProviderTest extends TestCase
{
    public function test_it_creates_and_reuses_the_scheduler_runtime_directory(): void
    {
        $path = sys_get_temp_dir() . '/project-yii-operations-' . bin2hex(random_bytes(5));
        $provider = new OperationsProvider(new ProviderContext('/project', ['app.scheduler_path' => $path]));
        self::assertNotEmpty($provider->getDefinitions());
        self::assertDirectoryExists($path);
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());
    }

    public function test_it_fails_when_the_scheduler_directory_cannot_be_created(): void
    {
        $file = tempnam(sys_get_temp_dir(), 'project-yii-operations-file-');
        self::assertNotFalse($file);
        $provider = new OperationsProvider(new ProviderContext('/project', ['app.scheduler_path' => $file . '/nested']));

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
