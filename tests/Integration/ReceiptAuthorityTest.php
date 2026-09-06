<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use Fight\Release\Application\StarterSupportReceiptAuthority;
use PHPUnit\Framework\TestCase;

final class ReceiptAuthorityTest extends TestCase
{
    /** @var array<string, mixed> */
    private array $receipt;
    private string $root;

    protected function setUp(): void
    {
        $this->root = dirname(__DIR__, 2);
        require_once $this->root.'/scripts/framework-support-receipt.php';
        require_once $this->root.'/vendor/johnnickell/fight-common/release/src/Application/StarterSupportReceiptAuthority.php';
        $receipt = json_decode(
            (string) file_get_contents($this->root.'/evidence/framework-support/receipt-v1.json'),
            true,
            flags: JSON_THROW_ON_ERROR,
        );
        self::assertIsArray($receipt);
        $this->receipt = $receipt;
    }

    public function test_committed_receipt_is_deterministic_and_digests_are_independently_recomputed(): void
    {
        self::assertSame(
            frameworkSupportCanonicalJson(frameworkSupportReceipt($this->root)),
            (string) file_get_contents($this->root.'/evidence/framework-support/receipt-v1.json'),
        );

        $content = $this->receipt;
        $content['content_id'] = str_repeat('0', 64);
        $content['evidence']['receipt_sha256'] = str_repeat('0', 64);
        $digest = $this->receipt;
        $digest['evidence']['receipt_sha256'] = str_repeat('0', 64);

        self::assertSame($this->receipt['content_id'], hash('sha256', json_encode($content, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)));
        self::assertSame($this->receipt['evidence']['receipt_sha256'], hash('sha256', json_encode($digest, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)));
        self::assertSame(hash_file('sha256', $this->root.'/composer.lock'), $this->receipt['lock_sha256']);
    }

    public function test_external_authority_accepts_only_a_complete_passing_current_receipt(): void
    {
        $authority = new StarterSupportReceiptAuthority();

        self::assertTrue($authority->isValid($this->receipt));
        self::assertSame(
            [
                'view.native_yii' => 'ship',
                'messaging.symfony_messenger_fallback' => 'wire',
                'messaging.stable_yii_queue' => 'unavailable',
                'filesystem.native_yii' => 'unavailable',
                'filesystem.symfony_fallback' => 'wire',
                'mail.native_yii' => 'unavailable',
                'mail.symfony_fallback' => 'wire',
            ],
            array_intersect_key(
                $this->receipt['capabilities'],
                array_flip([
                    'view.native_yii',
                    'mail.native_yii',
                    'mail.symfony_fallback',
                    'filesystem.native_yii',
                    'filesystem.symfony_fallback',
                    'messaging.symfony_messenger_fallback',
                    'messaging.stable_yii_queue',
                ]),
            ),
        );
        self::assertSame('passed', $this->receipt['result']);
        self::assertNull($this->receipt['next_action']);
        self::assertContains(
            ['name' => 'bounded_provider_groups', 'status' => 'passed', 'evidence' => 'tests/Integration/CapabilityProviderIsolationTest.php'],
            $this->receipt['journeys'],
        );
        foreach ($this->receipt['journeys'] as $journey) {
            self::assertSame('passed', $journey['status']);
        }
        self::assertTrue($this->projectAccepts($this->receipt, $authority));
    }

    public function test_valid_non_passing_receipts_require_exactly_one_resumable_action_and_fail_project_acceptance(): void
    {
        $authority = new StarterSupportReceiptAuthority();

        foreach (['failed', 'unavailable', 'skipped', 'indeterminate'] as $status) {
            $receipt = $this->receipt;
            $receipt['result'] = $status;
            $receipt['journeys'][0]['status'] = $status;
            $receipt['next_action'] = ['action' => 'repair_'.$status.'_journey'];
            $receipt = frameworkSupportWithDigests($receipt);

            self::assertTrue($authority->isValid($receipt), $status);
            self::assertFalse($this->projectAccepts($receipt, $authority), $status);

            $withoutAction = $receipt;
            $withoutAction['next_action'] = null;
            self::assertFalse($authority->isValid($withoutAction), $status.' without an action');

            $multipleActions = $receipt;
            $multipleActions['next_action']['follow_up'] = 'do_not_accept';
            self::assertFalse($authority->isValid($multipleActions), $status.' with multiple actions');
        }
    }

    public function test_missing_malformed_stale_candidate_mismatch_and_conflicted_receipts_fail_closed(): void
    {
        $authority = new StarterSupportReceiptAuthority();

        $missing = $this->receipt;
        unset($missing['lock_sha256']);
        self::assertFalse($authority->isValid($missing));
        self::assertFalse($this->projectAccepts($missing, $authority));

        $malformed = $this->receipt;
        $malformed['candidate']['version'] = 'not-a-version';
        self::assertFalse($authority->isValid($malformed));
        self::assertFalse($this->projectAccepts($malformed, $authority));

        $stale = $this->receipt;
        $stale['lock_sha256'] = str_repeat('a', 64);
        $stale = frameworkSupportWithDigests($stale);
        self::assertTrue($authority->isValid($stale));
        self::assertFalse($this->projectAccepts($stale, $authority));

        $candidateMismatch = $this->receipt;
        $candidateMismatch['candidate']['reference'] = str_repeat('b', 40);
        $candidateMismatch = frameworkSupportWithDigests($candidateMismatch);
        self::assertTrue($authority->isValid($candidateMismatch));
        self::assertFalse($this->projectAccepts($candidateMismatch, $authority));

        $this->expectException(\JsonException::class);
        json_decode("{\n<<<<<<< HEAD\n}\n=======\n{}\n>>>>>>> branch", true, flags: JSON_THROW_ON_ERROR);
    }

    /** @param array<string, mixed> $receipt */
    private function projectAccepts(array $receipt, StarterSupportReceiptAuthority $authority): bool
    {
        return $authority->isValid($receipt)
            && ($receipt['result'] ?? null) === 'passed'
            && ($receipt['candidate']['reference'] ?? null) === FRAMEWORK_SUPPORT_CANDIDATE_REFERENCE
            && frameworkSupportReceiptMatchesCurrentEvidence($receipt, $this->root);
    }
}
