<?php

declare(strict_types=1);

const FRAMEWORK_SUPPORT_SCHEMA = 'fight-common.framework-support-receipt/v1';
const FRAMEWORK_SUPPORT_VERSION = '1.2.0-dev';

require_once __DIR__.'/framework-support-profile.php';

/** @param array<string, mixed> $receipt */
function frameworkSupportDigest(array $receipt, bool $zeroContentId, bool $zeroReceiptDigest): string
{
    if ($zeroContentId) {
        $receipt['content_id'] = str_repeat('0', 64);
    }
    if ($zeroReceiptDigest) {
        $receipt['evidence']['receipt_sha256'] = str_repeat('0', 64);
    }

    return hash('sha256', json_encode($receipt, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
}

/** @param array<string, mixed> $receipt @return array<string, mixed> */
function frameworkSupportWithDigests(array $receipt): array
{
    $receipt['content_id'] = frameworkSupportDigest($receipt, true, true);
    $receipt['evidence']['receipt_sha256'] = frameworkSupportDigest($receipt, false, true);

    return $receipt;
}

/** @param array<string, mixed> $receipt */
function frameworkSupportCanonicalJson(array $receipt): string
{
    return json_encode($receipt, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n";
}

/** @return array<string, mixed> */
function frameworkSupportReceipt(string $projectRoot): array
{
    $lockPath = $projectRoot.'/composer.lock';
    $packages = frameworkSupportLockPackages($lockPath);
    $profile = frameworkSupportProfile($projectRoot);
    frameworkSupportAssertPackageMatrix($packages, $profile, 'composer.lock');
    $version = static fn (string $package): string => $packages[$package]['version']
        ?? throw new RuntimeException(sprintf('Receipt provider is missing from composer.lock: %s', $package));
    $providerPackages = [
        'dragonmantank/cron-expression', 'guzzlehttp/guzzle', 'league/flysystem', 'league/flysystem-local',
        'nyholm/psr7', 'symfony/cache', 'symfony/filesystem', 'symfony/mailer', 'symfony/mercure', 'symfony/messenger',
        'symfony/process', 'yiisoft/cache', 'yiisoft/config', 'yiisoft/db', 'yiisoft/db-sqlite', 'yiisoft/di',
        'yiisoft/event-dispatcher', 'yiisoft/log', 'yiisoft/mailer', 'yiisoft/router', 'yiisoft/router-fastroute',
        'yiisoft/session', 'yiisoft/validator', 'yiisoft/view', 'yiisoft/view-twig', 'yiisoft/yii-console', 'yiisoft/yii-http',
    ];

    return frameworkSupportWithDigests([
        'schema_version' => FRAMEWORK_SUPPORT_SCHEMA,
        'content_id' => str_repeat('0', 64),
        'candidate' => [
            'package' => FRAMEWORK_SUPPORT_CANDIDATE_PACKAGE,
            'version' => FRAMEWORK_SUPPORT_VERSION,
            'reference' => FRAMEWORK_SUPPORT_CANDIDATE_REFERENCE,
        ],
        'framework' => [
            'name' => 'yii',
            'version' => $version('yiisoft/yii-http'),
            'providers' => array_map(static fn (string $package): string => $package.'@'.$version($package), $providerPackages),
        ],
        'lock_sha256' => hash_file('sha256', $lockPath),
        'capabilities' => [
            'container.bounded_yii_providers' => 'ship',
            'request.routing_twig' => 'ship',
            'view.native_yii' => 'ship',
            'security.password_and_validation' => 'wire',
            'messaging.synchronous' => 'ship',
            'messaging.symfony_messenger_fallback' => 'wire',
            'messaging.stable_yii_queue' => 'unavailable',
            'persistence.sqlite_transactions' => 'ship',
            'storage.flysystem_local' => 'wire',
            'filesystem.native_yii' => 'unavailable',
            'filesystem.symfony_fallback' => 'wire',
            'http.guzzle_psr18' => 'wire',
            'process_and_scheduler' => 'wire',
            'mail.native_yii' => 'unavailable',
            'mail.symfony_fallback' => 'wire',
            'sms_and_transfer_null_fallbacks' => 'wire',
            'observability.null_fallbacks' => 'wire',
            'publication.mercure_mock_fallback' => 'wire',
            'cache.psr6' => 'wire',
        ],
        'journeys' => [
            ['name' => 'bounded_provider_groups', 'status' => 'passed', 'evidence' => 'tests/Integration/CapabilityProviderIsolationTest.php'],
            ['name' => 'booted_request_lifecycle', 'status' => 'passed', 'evidence' => 'tests/Integration/HttpApplicationJourneyTest.php'],
            ['name' => 'booted_messaging_lifecycle', 'status' => 'passed', 'evidence' => 'tests/Integration/MessagingJourneyTest.php'],
            ['name' => 'booted_stateful_lifecycle', 'status' => 'passed', 'evidence' => 'tests/Integration/StatefulCapabilityJourneyTest.php'],
            ['name' => 'booted_integration_fallbacks', 'status' => 'passed', 'evidence' => 'tests/Integration/IntegrationFallbackJourneyTest.php'],
            ['name' => 'lowest_locked_yii_capabilities', 'status' => 'passed', 'evidence' => 'composer-lowest.lock; scripts/verify-dependency-lanes.sh'],
            ['name' => 'latest_and_production_yii_capabilities', 'status' => 'passed', 'evidence' => 'composer.lock; scripts/verify-production-framework-support-profile.php'],
        ],
        'result' => 'passed',
        'evidence' => [
            'build' => './bin/build',
            'planning_check' => './bin/planning-check',
            'receipt_sha256' => str_repeat('0', 64),
        ],
        'next_action' => null,
    ]);
}

/** @param array<string, mixed> $receipt */
function frameworkSupportReceiptMatchesCurrentEvidence(array $receipt, string $projectRoot): bool
{
    return hash_equals(frameworkSupportCanonicalJson(frameworkSupportReceipt($projectRoot)), frameworkSupportCanonicalJson($receipt));
}
