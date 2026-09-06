<?php

declare(strict_types=1);

function assertProjectArchitecture(string $root): void
{
    $architecture = (string) file_get_contents($root . '/ARCHITECTURE.md');
    $providers = require $root . '/config/providers.php';
    require_once $root . '/scripts/source-boundary.php';

    if (!is_array($providers)) {
        throw new RuntimeException('The provider graph must be a PHP array.');
    }

    foreach (['configuration providers', 'dependency injection', 'web and console entry points', 'presentation'] as $boundary) {
        if (!str_contains($architecture, $boundary)) {
            throw new RuntimeException(sprintf('Architecture must declare the Yii-owned %s boundary.', $boundary));
        }
    }

    if (preg_match('/published\s+Composer contracts/', $architecture) !== 1) {
        throw new RuntimeException('Architecture must prohibit unpublished shared-package coupling.');
    }

    $requiredProviders = [
        'routing-policy' => 'App\\Adapter\\Container\\RoutingProvider',
        'view-policy' => 'App\\Adapter\\Container\\ViewProvider',
        'http-application' => 'App\\Adapter\\Container\\HttpApplicationProvider',
        'security-and-validation' => 'App\\Adapter\\Container\\SecurityAndValidationProvider',
        'synchronous-messaging-policy' => 'App\\Adapter\\Container\\SynchronousMessagingProvider',
        'messenger-fallback-policy' => 'App\\Adapter\\Container\\MessengerFallbackProvider',
        'persistence-policy' => 'App\\Adapter\\Container\\PersistenceProvider',
        'files-policy' => 'App\\Adapter\\Container\\FilesProvider',
        'http-client-policy' => 'App\\Adapter\\Container\\HttpClientProvider',
        'operations-policy' => 'App\\Adapter\\Container\\OperationsProvider',
        'mail-policy' => 'App\\Adapter\\Container\\MailProvider',
        'sms-policy' => 'App\\Adapter\\Container\\SmsProvider',
        'publication-policy' => 'App\\Adapter\\Container\\PublicationProvider',
        'observability-policy' => 'App\\Adapter\\Container\\ObservabilityProvider',
        'fight-common-filesystem' => 'Fight\\Common\\Adapter\\ServiceContainer\\Yii\\FilesystemServiceProvider',
        'fight-common-http-client' => 'Fight\\Common\\Adapter\\ServiceContainer\\Yii\\HttpClientServiceProvider',
        'fight-common-mail' => 'Fight\\Common\\Adapter\\ServiceContainer\\Yii\\MailServiceProvider',
        'fight-common-messaging' => 'Fight\\Common\\Adapter\\ServiceContainer\\Yii\\MessagingServiceProvider',
        'fight-common-persistence' => 'Fight\\Common\\Adapter\\ServiceContainer\\Yii\\PersistenceServiceProvider',
        'fight-common-routing' => 'Fight\\Common\\Adapter\\ServiceContainer\\Yii\\RoutingServiceProvider',
        'fight-common-view' => 'Fight\\Common\\Adapter\\ServiceContainer\\Yii\\ViewServiceProvider',
    ];
    foreach ($requiredProviders as $capability => $providerClass) {
        $value = $providers[$capability] ?? null;
        $actualClass = is_array($value) ? ($value['class'] ?? null) : $value;
        if ($actualClass !== $providerClass) {
            throw new RuntimeException(sprintf('The provider graph must compose %s as %s.', $capability, $providerClass));
        }
    }

    $forbiddenAggregateProviders = [
        'App\\Adapter\\Container\\ApplicationProvider' => 'src/Adapter/Container/ApplicationProvider.php',
        'App\\Adapter\\Container\\CommunicationProvider' => 'src/Adapter/Container/CommunicationProvider.php',
        'App\\Adapter\\Container\\CompletePlatformProvider' => 'src/Adapter/Container/CompletePlatformProvider.php',
    ];
    foreach ($forbiddenAggregateProviders as $providerClass => $providerPath) {
        $containsClass = in_array($providerClass, $providers, true)
            || array_filter($providers, fn($v) => is_array($v) && ($v['class'] ?? null) === $providerClass) !== [];
        if ($containsClass || is_file($root . '/' . $providerPath)) {
            throw new RuntimeException(sprintf('The bounded provider graph must not retain aggregate provider %s at %s.', $providerClass, $providerPath));
        }
    }

    assertProjectSourceBoundary($root . '/src');
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    assertProjectArchitecture(dirname(__DIR__));
    fwrite(STDOUT, "Architecture boundary contract passed.\n");
}
