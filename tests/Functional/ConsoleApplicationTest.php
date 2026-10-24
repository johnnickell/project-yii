<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

/**
 * Class ConsoleApplicationTest
 */
#[CoversNothing]
final class ConsoleApplicationTest extends TestCase
{
    /**
     * Tests native console command listing
     */
    public function testTheYiiConsoleEntryPointHandlesList(): void
    {
        $root = dirname(__DIR__, 2);
        $command = sprintf(
            'cd %s && %s scripts/console.php list 2>&1',
            escapeshellarg($root),
            escapeshellarg(PHP_BINARY)
        );
        exec($command, $output, $exitCode);

        self::assertSame(0, $exitCode, implode("\n", $output));
        self::assertStringContainsString('serve', implode("\n", $output));
    }
}
