<?php

declare(strict_types=1);

/** Rejects only copied shared namespaces while allowing project-owned Adapter/Application/Domain layers. */
function assertProjectSourceBoundary(string $sourceRoot): void
{
    if (!is_dir($sourceRoot)) {
        throw new RuntimeException(sprintf('Source root is missing: %s', $sourceRoot));
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceRoot, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (!$file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $source = (string) file_get_contents($file->getPathname());
        if (preg_match('/\bnamespace\s+Fight\\\\(?:Common|AccessControl)(?:\\\\|;)/', $source) === 1) {
            throw new RuntimeException(sprintf('Copied shared namespace declared in %s.', $file->getPathname()));
        }
    }
}
