<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\FilesProvider;
use App\Adapter\Container\ProviderContext;
use Fight\Common\Application\FileStorage\FileStorage;
use League\Flysystem\FilesystemOperator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(FilesProvider::class)]
final class FilesProviderTest extends TestCase
{
    public function test_it_binds_local_storage_to_the_configured_path(): void
    {
        $root = sys_get_temp_dir() . '/project-yii-files-' . bin2hex(random_bytes(5));
        $provider = new FilesProvider(new ProviderContext($root, ['app.storage_path' => 'storage']));
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());

        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 4))->createContainer(
            ['app.storage_path' => $root . '/storage'],
            ['files-policy'],
        );
        self::assertInstanceOf(FilesystemOperator::class, $container->get(FilesystemOperator::class));
        $container->get(FileStorage::class)->putFile('proof.txt', 'stored');
        self::assertSame('stored', $container->get(FileStorage::class)->getFileContents('proof.txt'));
    }
}
