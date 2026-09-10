<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\FileStorage\FlysystemStorage;
use Fight\Common\Adapter\FileTransfer\Null\NullFileTransport;
use Fight\Common\Application\FileStorage\FileStorage;
use Fight\Common\Application\FileTransfer\Transport\FileTransport;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use Yiisoft\Di\ServiceProviderInterface;

final readonly class FilesProvider implements ServiceProviderInterface
{
    public function __construct(private ProviderContext $context)
    {
    }

    /** @return array<string, mixed> */
    public function getDefinitions(): array
    {
        $storagePath = $this->context->absolutePath($this->context->parameters['app.storage_path']);

        return [
            SymfonyFilesystem::class => SymfonyFilesystem::class,
            FilesystemOperator::class => static fn (): FilesystemOperator =>
                new Filesystem(new LocalFilesystemAdapter($storagePath)),
            FileStorage::class => FlysystemStorage::class,
            FileTransport::class => NullFileTransport::class,
        ];
    }

    /** @return array<string, mixed> */
    public function getExtensions(): array
    {
        return [];
    }
}
