<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$architecture = (string) file_get_contents($root . '/ARCHITECTURE.md');
$provider = (string) file_get_contents($root . '/config/providers.php');
require $root.'/scripts/source-boundary.php';

foreach (['configuration providers', 'dependency injection', 'web and console entry points', 'presentation'] as $boundary) {
    if (!str_contains($architecture, $boundary)) {
        throw new RuntimeException(sprintf('Architecture must declare the Yii-owned %s boundary.', $boundary));
    }
}

if (preg_match('/published\s+Composer contracts/', $architecture) !== 1) {
    throw new RuntimeException('Architecture must prohibit unpublished shared-package coupling.');
}

foreach (['ApplicationProvider::class', 'FilesystemServiceProvider::class', 'HttpClientServiceProvider::class', 'MailServiceProvider::class', 'MessagingServiceProvider::class', 'PersistenceServiceProvider::class', 'RoutingServiceProvider::class', 'ViewServiceProvider::class'] as $providerClass) {
    if (!str_contains($provider, $providerClass)) {
        throw new RuntimeException(sprintf('The provider graph must compose %s.', $providerClass));
    }
}

if (is_file($root.'/src/Infrastructure/Container/CompletePlatformProvider.php')) {
    throw new RuntimeException('The catch-all CompletePlatformProvider must not remain in the bounded composition.');
}

assertProjectSourceBoundary($root.'/src');

fwrite(STDOUT, "Architecture boundary contract passed.\n");
