<?php

declare(strict_types=1);

const FRAMEWORK_SUPPORT_CANDIDATE_PACKAGE = 'johnnickell/fight-common';
const FRAMEWORK_SUPPORT_CANDIDATE_CONSTRAINT = 'dev-develop#4a798b1db8fdb5e4af7d0ba8c98a88ac53c50c16 as 1.2.0-dev';
const FRAMEWORK_SUPPORT_CANDIDATE_REFERENCE = '4a798b1db8fdb5e4af7d0ba8c98a88ac53c50c16';
const FRAMEWORK_SUPPORT_CANDIDATE_REPOSITORY = 'https://github.com/johnnickell/fight-common';

/** @return array<string, mixed> */
function frameworkSupportProfile(string $projectRoot): array
{
    $manifest = frameworkSupportJson($projectRoot.'/composer.json');
    frameworkSupportAssertManifest($manifest);

    return [
        'selected_direct_runtime_packages' => frameworkSupportSelectedDirectRuntimePackages(),
        'forbidden_runtime_packages' => [
            'codeigniter4/framework', 'laravel/framework', 'slim/slim', 'symfony/framework-bundle',
            'yiisoft/db-mysql', 'yiisoft/db-pgsql', 'yiisoft/mailer', 'yiisoft/queue', 'yiisoft/session',
            'phpseclib/phpseclib', 'twilio/sdk',
        ],
    ];
}

/** @return list<string> */
function frameworkSupportSelectedDirectRuntimePackages(): array
{
    return [
        'dragonmantank/cron-expression', 'guzzlehttp/guzzle', 'johnnickell/fight-access-control',
        'johnnickell/fight-common', 'lcobucci/jwt', 'league/flysystem', 'league/flysystem-local', 'nyholm/psr7',
        'symfony/filesystem', 'symfony/cache', 'symfony/mailer', 'symfony/mercure', 'symfony/messenger', 'symfony/process',
        'yiisoft/cache', 'yiisoft/config', 'yiisoft/db', 'yiisoft/db-sqlite', 'yiisoft/di',
        'yiisoft/event-dispatcher', 'yiisoft/log', 'yiisoft/router', 'yiisoft/router-fastroute', 'yiisoft/validator',
        'yiisoft/view', 'yiisoft/view-twig', 'yiisoft/yii-console', 'yiisoft/yii-http',
    ];
}

/** @param array<string, mixed> $manifest */
function frameworkSupportAssertManifest(array $manifest): void
{
    $requirements = array_keys($manifest['require'] ?? []);
    $requirements = array_values(array_filter($requirements, static fn (string $package): bool => $package !== 'php'));
    sort($requirements);
    $expected = frameworkSupportSelectedDirectRuntimePackages();
    sort($expected);
    if ($requirements !== $expected) {
        throw new RuntimeException('composer.json direct runtime requirements do not match the selected Yii profile.');
    }
    if (($manifest['require'][FRAMEWORK_SUPPORT_CANDIDATE_PACKAGE] ?? null) !== FRAMEWORK_SUPPORT_CANDIDATE_CONSTRAINT) {
        throw new RuntimeException('Fight Common must use the authorized immutable candidate constraint.');
    }
    if (($manifest['require']['league/flysystem'] ?? null) !== '^3.36'
        || ($manifest['require']['league/flysystem-local'] ?? null) !== '^3.35') {
        throw new RuntimeException('Flysystem and Local must retain their compatible selected generation.');
    }

    frameworkSupportAssertCandidateRepository($manifest);
}

/** @param array<string, mixed> $manifest */
function frameworkSupportAssertCandidateRepository(array $manifest): void
{
    foreach ($manifest['repositories'] ?? [] as $repository) {
        if (!is_array($repository)) {
            continue;
        }
        if (($repository['type'] ?? null) === 'vcs'
            && ($repository['url'] ?? null) === FRAMEWORK_SUPPORT_CANDIDATE_REPOSITORY) {
            return;
        }
    }

    throw new RuntimeException('Fight Common must resolve through its public VCS repository.');
}

/** @return array<string, mixed> */
function frameworkSupportJson(string $path): array
{
    if (!is_file($path)) {
        throw new RuntimeException(sprintf('Required JSON manifest is missing: %s', $path));
    }

    $decoded = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
    if (!is_array($decoded)) {
        throw new RuntimeException(sprintf('JSON manifest is not an object: %s', $path));
    }

    return $decoded;
}

/** @return array<string, array<string, mixed>> */
function frameworkSupportLockPackages(string $path): array
{
    $packages = [];
    foreach (frameworkSupportJson($path)['packages'] ?? [] as $package) {
        if (is_array($package) && is_string($package['name'] ?? null)) {
            $packages[$package['name']] = $package;
        }
    }

    return $packages;
}

/** @return array<string, array<string, mixed>> */
function frameworkSupportInstalledPackages(string $path): array
{
    $decoded = frameworkSupportJson($path);
    $packages = [];
    foreach ($decoded['packages'] ?? $decoded as $package) {
        if (is_array($package) && is_string($package['name'] ?? null)) {
            $packages[$package['name']] = $package;
        }
    }

    return $packages;
}

/** @param array<string, array<string, mixed>> $packages @param array<string, mixed> $profile */
function frameworkSupportAssertPackageMatrix(array $packages, array $profile, string $source): void
{
    $candidate = $packages[FRAMEWORK_SUPPORT_CANDIDATE_PACKAGE] ?? null;
    $reference = $candidate['source']['reference'] ?? $candidate['dist']['reference'] ?? null;
    if (($candidate['version'] ?? null) !== 'dev-develop' || $reference !== FRAMEWORK_SUPPORT_CANDIDATE_REFERENCE) {
        throw new RuntimeException(sprintf('Fight Common candidate identity is not exact in %s.', $source));
    }
    foreach ($profile['selected_direct_runtime_packages'] as $package) {
        if (!isset($packages[$package])) {
            throw new RuntimeException(sprintf('Selected runtime package is missing from %s: %s', $source, $package));
        }
    }
    foreach ($profile['forbidden_runtime_packages'] as $package) {
        if (isset($packages[$package])) {
            throw new RuntimeException(sprintf('Unselected runtime package is present in %s: %s', $source, $package));
        }
    }
}

/** @param array<string, mixed> $expectedProfile */
function frameworkSupportAssertLane(string $root, array $expectedProfile): void
{
    if (frameworkSupportProfile($root) !== $expectedProfile) {
        throw new RuntimeException('Lane manifest changed the selected Yii profile.');
    }
    frameworkSupportAssertPackageMatrix(frameworkSupportLockPackages($root.'/composer.lock'), $expectedProfile, 'composer.lock');
    frameworkSupportAssertPackageMatrix(frameworkSupportInstalledPackages($root.'/vendor/composer/installed.json'), $expectedProfile, 'installed.json');
}

if (PHP_SAPI === 'cli' && realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    try {
        $root = dirname(__DIR__);
        $profile = frameworkSupportProfile($root);
        if (($argv[1] ?? '') === '--assert-lane' && isset($argv[2])) {
            frameworkSupportAssertLane($argv[2], $profile);
        } elseif (($argv[1] ?? '') === '--candidate-reference') {
            fwrite(STDOUT, FRAMEWORK_SUPPORT_CANDIDATE_REFERENCE.PHP_EOL);
        } else {
            throw new RuntimeException('Usage: framework-support-profile.php --assert-lane <root>|--candidate-reference');
        }
    } catch (Throwable $exception) {
        fwrite(STDERR, $exception->getMessage().PHP_EOL);
        exit(1);
    }
}
