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
use Yiisoft\Di\ServiceProviderInterface;

final readonly class FilesProvider implements ServiceProviderInterface
{
    /** @param array<string, string> $parameters */
    public function __construct(private string $root, private array $parameters)
    {
    }

    public function getDefinitions(): array
    {
        $storagePath = $this->absolutePath($this->parameters['app.storage_path']);

        return [
            FilesystemOperator::class => static fn (): FilesystemOperator =>
                new Filesystem(new LocalFilesystemAdapter($storagePath)),
            FileStorage::class => FlysystemStorage::class,
            FileTransport::class => NullFileTransport::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }

    private function absolutePath(string $path): string
    {
        return str_starts_with($path, '/') ? $path : $this->root.'/'.$path;
    }
}
