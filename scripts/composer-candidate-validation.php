<?php

declare(strict_types=1);

/**
 * Temporary candidate-validation behavior. Remove this allowlist when Fight
 * Common 1.2 has a release tag and the immutable commit requirement is gone.
 */
$expectedWarning = '- The package "johnnickell/fight-common" is pointing to a commit-ref, this is bad practice and can cause unforeseen issues.';
$output = [];
$exitCode = 0;
exec('composer validate 2>&1', $output, $exitCode);

if ($exitCode !== 0) {
    throw new RuntimeException("Composer validation failed:\n".implode("\n", $output));
}

$warnings = array_values(array_filter(
    $output,
    static fn (string $line): bool => str_starts_with($line, '- ') || str_starts_with($line, 'Warning:')
));

if ($warnings !== [$expectedWarning]) {
    throw new RuntimeException(sprintf(
        "Composer validation emitted an unapproved warning:\n%s",
        implode("\n", $warnings)
    ));
}

fwrite(STDOUT, "Composer candidate validation passed.\n");
