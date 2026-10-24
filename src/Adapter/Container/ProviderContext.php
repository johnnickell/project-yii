<?php

declare(strict_types=1);

namespace App\Adapter\Container;

/**
 * Class ProviderContext
 */
final readonly class ProviderContext
{
    /**
     * Constructs ProviderContext
     *
     * @param string $root
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        public string $root,
        public array $parameters
    ) {
    }

    /**
     * Resolves a runtime path relative to the application root
     */
    public function absolutePath(string $path): string
    {
        return str_starts_with($path, '/') ? $path : $this->root.'/'.$path;
    }
}
