<?php

declare(strict_types=1);

$root = dirname(__DIR__);

foreach (['README.md', 'docs/local-runtime.md'] as $document) {
    $contents = (string) file_get_contents($root . '/' . $document);
    foreach (['./bin/build', './bin/console', './bin/phpunit'] as $command) {
        if (!str_contains($contents, $command)) {
            throw new RuntimeException(sprintf('%s must document %s.', $document, $command));
        }
    }
}

fwrite(STDOUT, "Developer documentation contract passed.\n");
