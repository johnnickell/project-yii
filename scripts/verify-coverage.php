<?php

declare(strict_types=1);

if ($argc !== 3) {
    fwrite(STDERR, "Usage: php scripts/verify-coverage.php <clover.xml> <source-directory>\n");
    exit(2);
}

[$script, $cloverPath, $sourceDirectory] = $argv;
unset($script);

$sourceRoot = realpath($sourceDirectory);
if ($sourceRoot === false || !is_dir($sourceRoot)) {
    throw new RuntimeException(sprintf('Coverage source directory does not exist: %s', $sourceDirectory));
}

$sourceFiles = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($sourceRoot));
foreach ($iterator as $file) {
    if ($file->isFile() && strtolower($file->getExtension()) === 'php') {
        $contents = (string) file_get_contents($file->getPathname());
        if (preg_match('/(?:@codeCoverageIgnore(?:Start|End)?|CodeCoverageIgnore)/i', $contents) === 1) {
            throw new RuntimeException(sprintf('Coverage-ignore directive found in production source: %s', $file->getPathname()));
        }
        $realPath = $file->getRealPath();
        if ($realPath === false) {
            throw new RuntimeException(sprintf('Could not resolve production source file: %s', $file->getPathname()));
        }
        $sourceFiles[] = $realPath;
    }
}
sort($sourceFiles);

if (!is_file($cloverPath) || filesize($cloverPath) === 0) {
    throw new RuntimeException(sprintf('Clover coverage output is missing or empty: %s', $cloverPath));
}

$document = new DOMDocument();
$previous = libxml_use_internal_errors(true);
$loaded = $document->load($cloverPath, LIBXML_NONET);
$errors = libxml_get_errors();
libxml_clear_errors();
libxml_use_internal_errors($previous);
if (!$loaded || $errors !== []) {
    throw new RuntimeException(sprintf('Clover coverage output is malformed: %s', $cloverPath));
}

$xpath = new DOMXPath($document);
$metrics = $xpath->query('/coverage/project/metrics')->item(0);
if (!$metrics instanceof DOMElement) {
    throw new RuntimeException('Clover coverage output has no project metrics.');
}

$statements = filter_var($metrics->getAttribute('statements'), FILTER_VALIDATE_INT);
$coveredStatements = filter_var($metrics->getAttribute('coveredstatements'), FILTER_VALIDATE_INT);
if (!is_int($statements) || !is_int($coveredStatements) || $statements < 1) {
    throw new RuntimeException('Clover coverage statement metrics are missing or invalid.');
}

$coveredFiles = [];
foreach ($xpath->query('/coverage/project//file') as $fileNode) {
    if ($fileNode instanceof DOMElement) {
        $path = realpath($fileNode->getAttribute('name'));
        if ($path !== false && str_starts_with($path, $sourceRoot . DIRECTORY_SEPARATOR)) {
            $coveredFiles[] = $path;
        }
    }
}
$coveredFiles = array_values(array_unique($coveredFiles));
sort($coveredFiles);

$missingFiles = array_values(array_diff($sourceFiles, $coveredFiles));
if ($missingFiles !== []) {
    throw new RuntimeException(sprintf(
        "Clover coverage is missing production source files:\n%s",
        implode("\n", $missingFiles),
    ));
}

if ($coveredStatements !== $statements) {
    throw new RuntimeException(sprintf(
        'Production statement coverage is not exact: %d/%d statements covered.',
        $coveredStatements,
        $statements,
    ));
}

fwrite(STDOUT, sprintf("Exact production statement coverage: %d/%d (100%%).\n", $coveredStatements, $statements));
