<?php

declare(strict_types=1);

$root = dirname(__DIR__);

$requiredFiles = [
    'AGENTS.md',
    'ARCHITECTURE.md',
    'CONTRIBUTING.md',
    'LICENSE',
    'SECURITY.md',
    'planning/README.md',
    'planning/ROADMAP.md',
    'planning/agents/domain.md',
    'planning/agents/issue-tracker.md',
    'planning/agents/triage-labels.md',
    'planning/specs/00001-PRD.md',
    'planning/tickets/00001-TICKET.md',
    'planning/tickets/BOARD.md',
    '.github/workflows/build.yml',
    'bin/composer',
    'bin/phpunit',
    'bin/console',
    'bin/up',
    'bin/down',
    'bin/exec',
];

foreach ($requiredFiles as $file) {
    if (!is_file($root . '/' . $file)) {
        throw new RuntimeException(sprintf('Required governance artifact is missing: %s.', $file));
    }
}

foreach (['bin/composer', 'bin/phpunit', 'bin/console', 'bin/up', 'bin/down', 'bin/exec'] as $wrapper) {
    if (!is_executable($root . '/' . $wrapper)) {
        throw new RuntimeException(sprintf('Repository wrapper must be executable: %s.', $wrapper));
    }
}

$console = (string) file_get_contents($root . '/bin/console');
if (!str_contains($console, 'scripts/console.php')) {
    throw new RuntimeException('bin/console must delegate to the Yii-native console entry point.');
}

$workflow = (string) file_get_contents($root . '/.github/workflows/build.yml');
if (!str_contains($workflow, 'run: ./bin/build')) {
    throw new RuntimeException('Hosted CI must delegate to ./bin/build.');
}

foreach (['develop', 'main', "'release/**'"] as $branch) {
    if (substr_count($workflow, $branch) < 2) {
        throw new RuntimeException(sprintf('Hosted CI must cover push and pull requests for %s.', $branch));
    }
}

$localPrd = (string) file_get_contents($root . '/planning/specs/00001-PRD.md');
if (
    !str_contains($localPrd, 'id: PRD-00001')
    || !str_contains($localPrd, 'Local authority')
    || !str_contains($localPrd, 'Fight Common PRD-00016')
    || !str_contains($localPrd, 'Fight Common PRD-00018')
) {
    throw new RuntimeException('PRD-00001 must be the local product authority.');
}

foreach (glob($root . '/planning/specs/*-PRD.md') ?: [] as $specification) {
    if (basename($specification) !== '00001-PRD.md') {
        throw new RuntimeException('External provenance must be linked, not copied into a local non-00001 PRD.');
    }
}

$gitignore = (string) file_get_contents($root . '/.gitignore');
foreach (['/.runs/', '/var/'] as $ignoredPath) {
    if (!str_contains($gitignore, $ignoredPath)) {
        throw new RuntimeException(sprintf('.gitignore must ignore %s.', $ignoredPath));
    }
}

fwrite(STDOUT, "Local authority and delivery-gate contract passed.\n");
