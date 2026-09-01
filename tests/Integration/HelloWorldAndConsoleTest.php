<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use PHPUnit\Framework\TestCase;

final class HelloWorldAndConsoleTest extends TestCase
{
    public function testHelloWorldConfigurationAndDiIntegration(): void
    {
        $this->runRegressionScript('tests/Integration/hello-world.php');
    }

    public function testYiiNativeConsoleDelegation(): void
    {
        $this->runRegressionScript('tests/Integration/console.php');
    }

    public function testCompletePlatformDefaultServices(): void
    {
        $this->runRegressionScript('tests/Integration/complete-platform.php');
    }

    private function runRegressionScript(string $script): void
    {
        $root = dirname(__DIR__, 2);
        $command = sprintf('cd %s && php %s 2>&1', escapeshellarg($root), escapeshellarg($script));
        $output = [];
        $exitCode = 0;
        exec($command, $output, $exitCode);

        self::assertSame(0, $exitCode, implode("\n", $output));
    }
}
