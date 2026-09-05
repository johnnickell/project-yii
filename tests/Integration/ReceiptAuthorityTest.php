<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use PHPUnit\Framework\TestCase;

final class ReceiptAuthorityTest extends TestCase
{
    public function test_committed_receipt_digests_are_independently_recomputed(): void
    {
        $root = dirname(__DIR__, 2);
        $receipt = json_decode((string) file_get_contents($root.'/evidence/framework-support/receipt-v1.json'), true, flags: JSON_THROW_ON_ERROR);
        $content = $receipt;
        $content['content_id'] = str_repeat('0', 64);
        $content['evidence']['receipt_sha256'] = str_repeat('0', 64);
        $digest = $receipt;
        $digest['evidence']['receipt_sha256'] = str_repeat('0', 64);

        self::assertSame($receipt['content_id'], hash('sha256', json_encode($content, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)));
        self::assertSame($receipt['evidence']['receipt_sha256'], hash('sha256', json_encode($digest, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)));
        self::assertSame(hash_file('sha256', $root.'/composer.lock'), $receipt['lock_sha256']);
        self::assertSame('unavailable', $receipt['capabilities']['messaging.stable_yii_queue']);
        self::assertSame('wire', $receipt['capabilities']['messaging.symfony_messenger_fallback']);
        self::assertSame('passed', $receipt['result']);
        self::assertNull($receipt['next_action']);
    }
}
