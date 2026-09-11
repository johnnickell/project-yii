<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

#[CoversNothing]
final class ConsoleApplicationTest extends TestCase
{
    public function test_the_yii_console_entry_point_handles_list(): void
    {
        $root = dirname(__DIR__, 2);
        $command = sprintf('cd %s && %s scripts/console.php list 2>&1', escapeshellarg($root), escapeshellarg(PHP_BINARY));
        exec($command, $output, $exitCode);

        self::assertSame(0, $exitCode, implode("\n", $output));
        self::assertStringContainsString('serve', implode("\n", $output));
    }
}
