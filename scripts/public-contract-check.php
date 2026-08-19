<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$composerPath = $root.'/composer.json';

if (!is_file($composerPath)) {
    throw new RuntimeException('composer.json must declare the public Yii project contract.');
}

/** @var array<string, mixed> $composer */
$composer = json_decode((string) file_get_contents($composerPath), true, 512, JSON_THROW_ON_ERROR);

if (($composer['name'] ?? null) !== 'johnnickell/project-yii') {
    throw new RuntimeException('Composer identity must be johnnickell/project-yii.');
}

$required = [
    'johnnickell/fight-common',
    'johnnickell/fight-access-control',
    'yiisoft/di',
    'yiisoft/config',
    'yiisoft/yii-http',
    'yiisoft/event-dispatcher',
    'yiisoft/router',
    'yiisoft/router-fastroute',
    'yiisoft/view',
    'yiisoft/view-twig',
    'yiisoft/validator',
    'yiisoft/mailer',
    'yiisoft/cache',
    'yiisoft/db',
    'yiisoft/db-mysql',
    'yiisoft/db-pgsql',
    'yiisoft/session',
    'yiisoft/log',
];

$dependencies = $composer['require'] ?? [];
if (!is_array($dependencies)) {
    throw new RuntimeException('Composer require must be an object.');
}

foreach ($required as $package) {
    if (!array_key_exists($package, $dependencies)) {
        throw new RuntimeException(sprintf('Required public dependency %s is missing.', $package));
    }
}

foreach (['src/Domain', 'src/Application'] as $forbiddenDirectory) {
    if (is_dir($root.'/'.$forbiddenDirectory)) {
        throw new RuntimeException(sprintf('Copied shared layer %s must not exist.', $forbiddenDirectory));
    }
}

fwrite(STDOUT, "Public Composer contract passed.\n");
