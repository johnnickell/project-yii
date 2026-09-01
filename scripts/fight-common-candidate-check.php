<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$candidate = '4a798b1db8fdb5e4af7d0ba8c98a88ac53c50c16';
$constraint = 'dev-develop#'.$candidate.' as 1.2.0-dev';

/** @var array<string, mixed> $composer */
$composer = json_decode((string) file_get_contents($root.'/composer.json'), true, 512, JSON_THROW_ON_ERROR);
/** @var array<string, mixed> $lock */
$lock = json_decode((string) file_get_contents($root.'/composer.lock'), true, 512, JSON_THROW_ON_ERROR);

if (($composer['require']['johnnickell/fight-common'] ?? null) !== $constraint) {
    throw new RuntimeException('Fight Common must use the authorized exact Composer compatibility alias.');
}

$repository = $composer['repositories'][1] ?? null;
if (!is_array($repository) || ($repository['type'] ?? null) !== 'vcs' || ($repository['url'] ?? null) !== 'https://github.com/johnnickell/fight-common') {
    throw new RuntimeException('Fight Common must resolve through its public VCS repository.');
}

foreach ($lock['packages'] ?? [] as $package) {
    if (($package['name'] ?? null) !== 'johnnickell/fight-common') {
        continue;
    }

    if (($package['version'] ?? null) !== 'dev-develop' || ($package['source']['reference'] ?? null) !== $candidate) {
        throw new RuntimeException('Fight Common lock identity must be dev-develop at the exact candidate commit.');
    }

    fwrite(STDOUT, "Fight Common candidate identity passed.\n");

    return;
}

throw new RuntimeException('Fight Common must be present in composer.lock.');
