<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$composer = json_decode((string) file_get_contents($root . '/composer.json'), true, 512, JSON_THROW_ON_ERROR);

if (!isset($composer['require-dev']['phpunit/phpunit'])) {
    throw new RuntimeException('The repository must declare PHPUnit as a development dependency.');
}

if (!is_file($root . '/phpunit.xml')) {
    throw new RuntimeException('The repository must declare a PHPUnit suite configuration.');
}

$build = (string) file_get_contents($root . '/bin/build');
foreach (['./bin/phpunit', 'scripts/verify-dependency-lanes.sh', 'php scripts/verify-framework-support-receipt.php', 'php scripts/architecture-check.php', 'php scripts/docs-check.php'] as $requiredCommand) {
    if (!str_contains($build, $requiredCommand)) {
        throw new RuntimeException(sprintf('The canonical build must run %s.', $requiredCommand));
    }
}

fwrite(STDOUT, "Quality gate regression contract passed.\n");
