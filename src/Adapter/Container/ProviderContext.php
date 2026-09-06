<?php

declare(strict_types=1);

namespace App\Adapter\Container;

final readonly class ProviderContext
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        public string $root,
        public array $parameters,
    ) {}

    public function absolutePath(string $path): string
    {
        return str_starts_with($path, '/') ? $path : $this->root . '/' . $path;
    }
}