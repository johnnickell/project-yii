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
foreach ([
    './bin/planning-check',
    'scripts/verify-dependency-lanes.sh',
    'php scripts/verify-framework-support-receipt.php',
    'PROJECT_YII_IN_BUILD=1 ./bin/phpunit',
    'php scripts/verify-production-framework-support-profile.php',
    'php scripts/architecture-check.php',
    'php scripts/docs-check.php',
] as $requiredCommand) {
    if (!str_contains($build, $requiredCommand)) {
        throw new RuntimeException(sprintf('The canonical build must run %s.', $requiredCommand));
    }
}

$planningCheckOffset = strpos($build, './bin/planning-check');
$dockerBuildOffset = strpos($build, 'docker build');
if ($planningCheckOffset === false || $dockerBuildOffset === false || $planningCheckOffset > $dockerBuildOffset) {
    throw new RuntimeException('The canonical build must run planning validation before Docker isolation.');
}

if (str_contains($build, "image=project-yii-build\n")
    || !str_contains($build, "worktree_checksum=$(pwd -P | cksum | awk '{print $1}')")
    || !str_contains($build, 'image="project-yii-build-$worktree_checksum"')
) {
    throw new RuntimeException('The canonical build must use a stable Docker image identity derived from its physical worktree path.');
}

$dependencyLanes = (string) file_get_contents($root . '/scripts/verify-dependency-lanes.sh');
if (!str_contains($dependencyLanes, 'tests/Integration/CapabilityProviderIsolationTest.php')) {
    throw new RuntimeException('Each immutable dependency lane must exercise capability-provider isolation.');
}

$receipt = json_decode(
    (string) file_get_contents($root . '/evidence/framework-support/receipt-v1.json'),
    true,
    flags: JSON_THROW_ON_ERROR,
);
if (!is_array($receipt)) {
    throw new RuntimeException('The framework-support receipt must decode to an object.');
}

$references = [
    $receipt['evidence']['build'] ?? null,
    $receipt['evidence']['planning_check'] ?? null,
];
foreach ($receipt['journeys'] ?? [] as $journey) {
    foreach (explode('; ', (string) ($journey['evidence'] ?? '')) as $reference) {
        $references[] = $reference;
    }
}
foreach ($references as $reference) {
    if (!is_string($reference) || $reference === '' || !is_file($root . '/' . ltrim($reference, './'))) {
        throw new RuntimeException(sprintf('Receipt evidence must resolve to a repository-owned artifact: %s.', (string) $reference));
    }
}

fwrite(STDOUT, "Quality gate regression contract passed.\n");
