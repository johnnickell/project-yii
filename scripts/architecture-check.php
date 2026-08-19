<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$architecture = (string) file_get_contents($root . '/ARCHITECTURE.md');
$provider = (string) file_get_contents($root . '/config/providers.php');

foreach (['configuration providers', 'dependency injection', 'web and console entry points', 'presentation'] as $boundary) {
    if (!str_contains($architecture, $boundary)) {
        throw new RuntimeException(sprintf('Architecture must declare the Yii-owned %s boundary.', $boundary));
    }
}

if (preg_match('/published\s+Composer contracts/', $architecture) !== 1) {
    throw new RuntimeException('Architecture must prohibit unpublished shared-package coupling.');
}

if (!str_contains($provider, 'ApplicationProvider::class')) {
    throw new RuntimeException('The provider graph must compose the application provider.');
}

foreach (['src/Domain', 'src/Application'] as $forbiddenDirectory) {
    if (is_dir($root . '/' . $forbiddenDirectory)) {
        throw new RuntimeException(sprintf('Copied shared layer %s must not exist.', $forbiddenDirectory));
    }
}

fwrite(STDOUT, "Architecture boundary contract passed.\n");
