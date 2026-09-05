<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use PHPUnit\Framework\TestCase;

final class SourceBoundaryTest extends TestCase
{
    public function test_project_owned_adapter_application_and_domain_layers_are_valid(): void
    {
        require_once dirname(__DIR__, 2).'/scripts/source-boundary.php';
        $root = $this->fixtureRoot('valid');
        mkdir($root.'/Adapter', 0777, true);
        mkdir($root.'/Application', 0777, true);
        mkdir($root.'/Domain', 0777, true);
        file_put_contents($root.'/Adapter/Adapter.php', '<?php namespace App\\Adapter; final class Adapter {}');
        file_put_contents($root.'/Application/Service.php', '<?php namespace App\\Application; final class Service {}');
        file_put_contents($root.'/Domain/Thing.php', '<?php namespace App\\Domain; final class Thing {}');

        assertProjectSourceBoundary($root);
        self::addToAssertionCount(1);
    }

    public function test_a_copied_fight_namespace_fails_closed(): void
    {
        require_once dirname(__DIR__, 2).'/scripts/source-boundary.php';
        $root = $this->fixtureRoot('forbidden');
        mkdir($root, 0777, true);
        file_put_contents($root.'/Copied.php', '<?php namespace Fight\\Common\\Domain; final class Copied {}');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Copied shared namespace');
        assertProjectSourceBoundary($root);
    }

    private function fixtureRoot(string $name): string
    {
        return sys_get_temp_dir().'/project-yii-boundary-'.$name.'-'.bin2hex(random_bytes(5));
    }
}
