<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$command = sprintf('cd %s && php scripts/console.php list 2>&1', escapeshellarg($root));
$output = [];
$exitCode = 0;

exec($command, $output, $exitCode);

if ($exitCode !== 0) {
    throw new RuntimeException(sprintf("Yii console list command failed:\n%s", implode("\n", $output)));
}

$renderedOutput = implode("\n", $output);

if (!str_contains($renderedOutput, 'serve')) {
    throw new RuntimeException('Yii-native console must expose its installed serve command.');
}

fwrite(STDOUT, "Yii-native console delegation passed.\n");
