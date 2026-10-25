<?php

declare(strict_types=1);

// This is verification only. Environment and dependency preparation belong to bin/up and bin/composer.
chdir(dirname(__DIR__));

$setup = 'Run ./bin/up, then ./bin/composer install --no-interaction --prefer-dist --no-progress, then ./bin/build.';
$requiredFiles = [
    'autoload.php', 'bin/phpcs', 'bin/phpstan', 'bin/deptrac', 'bin/rector', 'bin/phpunit', 'bin/coverage-check'
];
foreach ($requiredFiles as $file) {
    if (!is_file('vendor/'.$file)) {
        fwrite(STDERR, "Build prerequisite missing: vendor/$file. $setup\n");
        exit(1);
    }
}
if (!extension_loaded('pcov') || !filter_var(ini_get('pcov.enabled'), FILTER_VALIDATE_BOOL)) {
    fwrite(STDERR, "Build requires enabled PCOV for Unit-only coverage. $setup\n");
    exit(1);
}

/**
 * Runs one named phase and stops immediately on an unexpected exit status
 *
 * @param string $name
 * @param list<string> $command
 * @param int $successExit
 */
$phase = static function (string $name, array $command, int $successExit = 0): void {
    fwrite(STDOUT, "\n==> $name\n");
    $process = proc_open($command, [STDIN, STDOUT, STDERR], $pipes);
    $exit = is_resource($process) ? proc_close($process) : 1;
    if ($exit !== $successExit) {
        fwrite(STDERR, "Phase failed: $name (exit $exit; expected $successExit). No later phases ran.\n");
        exit($exit === 0 ? 1 : $exit);
    }
};

$runtimeCheck = 'for tool in composer php-fpm python3 grep; do ';
$runtimeCheck .= 'command -v "$tool" >/dev/null || ';
$runtimeCheck .= '{ echo >&2 "Missing runtime tool: $tool; rebuild/recreate with ./bin/up."; exit 1; }; done';
$phase('Runtime prerequisites', ['sh', '-ec', $runtimeCheck]);
$phase('Planning freshness preflight (offline, read-only)', ['./bin/planning-check']);
$phase('FPM configuration', ['php-fpm', '-tt']);
$phase('Composer validation', ['composer', 'validate', '--strict']);
$phase('Installed platform requirements', ['composer', 'check-platform-reqs']);

$phpFiles = ['rector.php'];
foreach (['config', 'public', 'scripts', 'src', 'tests'] as $directory) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    foreach ($iterator as $file) {
        if ($file->isFile() && strtolower($file->getExtension()) === 'php') {
            $phpFiles[] = $file->getPathname();
        }
    }
}
sort($phpFiles);
$phase('PHP syntax', [PHP_BINARY, '-l', ...$phpFiles]);
// grep's exit 1 means no matches. Exit 0 (a directive) and errors both fail closed.
$phase('Reject production coverage-ignore directives', [
    'grep', '-RinE', '@codeCoverageIgnore|CodeCoverageIgnore', 'src'
], 1);
$phase('Common PHPCS formatting', [PHP_BINARY, 'vendor/bin/phpcs']);
$phase('PHPStan', [PHP_BINARY, 'vendor/bin/phpstan', 'analyse', '--no-progress']);
$phase('Deptrac inward boundaries', [
    PHP_BINARY, 'vendor/bin/deptrac', 'analyse', '--no-progress', '--fail-on-uncovered'
]);
$phase('Rector dry-run', [PHP_BINARY, 'vendor/bin/rector', 'process', '--dry-run', '--no-progress-bar']);

if (!is_dir('var/coverage') && !mkdir('var/coverage', 0777, true)) {
    fwrite(STDERR, "Cannot create var/coverage; check checkout permissions.\n");
    exit(1);
}
$clover = 'var/coverage/unit-clover.xml';
if (is_file($clover) && !unlink($clover)) {
    fwrite(STDERR, "Cannot remove stale $clover; check checkout permissions.\n");
    exit(1);
}
// Only Unit CoversClass metadata contributes. Integration and Functional declare CoversNothing.
$phase('PHPUnit — all configured suites once, Unit-only coverage', [
    PHP_BINARY, 'vendor/bin/phpunit', '--coverage-clover', $clover, '--coverage-text',
    '--log-junit', 'var/coverage/phpunit-junit.xml'
]);
$phase('Exact coverage threshold (100%, unrounded)', [PHP_BINARY, 'vendor/bin/coverage-check', $clover, '100']);
$phase('Final planning validation (offline, read-only)', ['./bin/planning-check']);
fwrite(STDOUT, "\nBuild passed.\n");
