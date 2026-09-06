<?php

declare(strict_types=1);

/** Rejects copied shared namespaces and enforces the project-owned production topology. */
function assertProjectSourceBoundary(string $sourceRoot): void
{
    if (!is_dir($sourceRoot)) {
        throw new RuntimeException(sprintf('Source root is missing: %s', $sourceRoot));
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceRoot, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') {
            continue;
        }

        $source = (string) file_get_contents($file->getPathname());
        if (preg_match('/\bnamespace\s+Fight\\\\(?:Common|AccessControl)(?:\\\\|;)/', $source) === 1) {
            throw new RuntimeException(sprintf('Copied shared namespace declared in %s.', $file->getPathname()));
        }

        $relativePath = substr($file->getPathname(), strlen(rtrim($sourceRoot, DIRECTORY_SEPARATOR)) + 1);
        [$pathLayer] = explode(DIRECTORY_SEPARATOR, $relativePath, 2);
        $namespaceMatched = preg_match('/\bnamespace\s+([^\s;]+)\s*;/', $source, $matches) === 1;
        $namespaceParts = $namespaceMatched ? explode('\\', $matches[1]) : [];
        $allowedLayers = ['Adapter', 'Application', 'Domain'];

        if (
            !in_array($pathLayer, $allowedLayers, true)
            || ($namespaceParts[0] ?? null) !== 'App'
            || !in_array($namespaceParts[1] ?? null, $allowedLayers, true)
            || ($namespaceParts[1] ?? null) !== $pathLayer
        ) {
            throw new RuntimeException(sprintf(
                'Project-owned PHP source must be in Adapter, Application, or Domain with a matching App namespace: %s.',
                $file->getPathname(),
            ));
        }
    }
}
