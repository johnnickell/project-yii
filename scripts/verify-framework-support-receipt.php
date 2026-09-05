<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$candidateRoot = $argv[1] ?? $projectRoot.'/vendor/johnnickell/fight-common';
$authorityFile = $candidateRoot.'/release/src/Application/StarterSupportReceiptAuthority.php';
if (!is_file($authorityFile)) {
    fwrite(STDERR, "Fight Common receipt authority is missing from the exact candidate checkout.\n");
    exit(1);
}
require $projectRoot.'/scripts/framework-support-receipt.php';
require $authorityFile;

$path = $projectRoot.'/evidence/framework-support/receipt-v1.json';
try {
    $receipt = json_decode((string) file_get_contents($path), true, flags: JSON_THROW_ON_ERROR);
} catch (Throwable $exception) {
    throw new RuntimeException('Framework-support receipt is missing, malformed, or conflicted.', 0, $exception);
}
if (!is_array($receipt)) {
    throw new RuntimeException('Framework-support receipt must decode to an object.');
}
$authority = new Fight\Release\Application\StarterSupportReceiptAuthority();
$accepts = static function (array $candidate) use ($authority, $projectRoot): bool {
    return $authority->isValid($candidate)
        && ($candidate['result'] ?? null) === 'passed'
        && ($candidate['candidate']['reference'] ?? null) === FRAMEWORK_SUPPORT_CANDIDATE_REFERENCE
        && frameworkSupportReceiptMatchesCurrentEvidence($candidate, $projectRoot);
};
if (!$accepts($receipt)) {
    throw new RuntimeException('Exact-candidate receipt authority rejected the committed current receipt.');
}

$contentCandidate = $receipt;
$contentCandidate['content_id'] = str_repeat('0', 64);
$contentCandidate['evidence']['receipt_sha256'] = str_repeat('0', 64);
$independentContent = hash('sha256', json_encode($contentCandidate, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
$digestCandidate = $receipt;
$digestCandidate['evidence']['receipt_sha256'] = str_repeat('0', 64);
$independentReceipt = hash('sha256', json_encode($digestCandidate, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR));
if (!hash_equals($receipt['content_id'], $independentContent) || !hash_equals($receipt['evidence']['receipt_sha256'], $independentReceipt)) {
    throw new RuntimeException('Independent receipt digest recomputation failed.');
}

$missing = $receipt;
unset($missing['lock_sha256']);
$malformed = $receipt;
$malformed['candidate']['version'] = 'not-a-version';
$stale = $receipt;
$stale['lock_sha256'] = str_repeat('a', 64);
$candidateMismatch = $receipt;
$candidateMismatch['candidate']['reference'] = str_repeat('b', 40);
$fixtures = compact('missing', 'malformed', 'stale', 'candidateMismatch');
foreach (['failed', 'unavailable', 'skipped', 'indeterminate'] as $status) {
    $fixture = $receipt;
    $fixture['result'] = $status;
    $fixture['journeys'][0]['status'] = $status;
    $fixture['next_action'] = ['action' => 'repair_'.$status.'_journey'];
    $fixtures[$status] = $fixture;
}
foreach ($fixtures as $name => $fixture) {
    if ($accepts($fixture)) {
        throw new RuntimeException(sprintf('Receipt verifier accepted fail-closed fixture: %s', $name));
    }
}
try {
    json_decode("{\n<<<<<<< HEAD\n}\n=======\n{}\n>>>>>>> branch", true, flags: JSON_THROW_ON_ERROR);
    throw new RuntimeException('Conflicted receipt fixture unexpectedly decoded.');
} catch (JsonException) {
}

fwrite(STDOUT, "Exact-candidate framework-support receipt authority passed.\n");
