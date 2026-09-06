<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use PHPUnit\Framework\Attributes\PreserveGlobalState;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use PHPUnit\Framework\TestCase;

final class ArchitectureCheckTest extends TestCase
{
    #[RunInSeparateProcess]
    #[PreserveGlobalState(false)]
    public function test_an_aggregate_provider_identity_in_the_evaluated_graph_fails_closed(): void
    {
        $projectRoot = dirname(__DIR__, 2);
        require_once $projectRoot . '/scripts/architecture-check.php';

        $root = sys_get_temp_dir() . '/project-yii-architecture-' . bin2hex(random_bytes(5));
        mkdir($root . '/config', 0777, true);
        mkdir($root . '/scripts', 0777, true);
        mkdir($root . '/src/Adapter', 0777, true);
        copy($projectRoot . '/ARCHITECTURE.md', $root . '/ARCHITECTURE.md');
        copy($projectRoot . '/scripts/source-boundary.php', $root . '/scripts/source-boundary.php');
        file_put_contents($root . '/src/Adapter/Placeholder.php', '<?php namespace App\\Adapter; final class Placeholder {}');

        /** @var array<string, class-string> $providers */
        $providers = require $projectRoot . '/config/providers.php';
        $providers['legacy-aggregate'] = 'App\\Adapter\\Container\\ApplicationProvider';
        file_put_contents($root . '/config/providers.php', '<?php return ' . var_export($providers, true) . ";\n");

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('must not retain aggregate provider App\\Adapter\\Container\\ApplicationProvider');
        assertProjectArchitecture($root);
    }
}
