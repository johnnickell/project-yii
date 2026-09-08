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

    public function test_project_owned_production_source_is_limited_to_the_adapter_application_and_domain_layers(): void
    {
        require_once dirname(__DIR__, 2).'/scripts/source-boundary.php';

        assertProjectSourceBoundary(dirname(__DIR__, 2).'/src');
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

    public function test_a_project_owned_php_file_outside_the_adapter_application_or_domain_layers_fails_closed(): void
    {
        require_once dirname(__DIR__, 2).'/scripts/source-boundary.php';
        $root = $this->fixtureRoot('invalid-project-layer');
        mkdir($root.'/Web', 0777, true);
        file_put_contents($root.'/Web/HomeHandler.php', '<?php namespace App\\Web; final class HomeHandler {}');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Project-owned PHP source must be in Adapter, Application, or Domain');
        assertProjectSourceBoundary($root);
    }

    public function test_a_copied_access_control_namespace_in_a_php_file_with_an_uppercase_extension_fails_closed(): void
    {
        require_once dirname(__DIR__, 2).'/scripts/source-boundary.php';
        $root = $this->fixtureRoot('forbidden-access-control');
        mkdir($root, 0777, true);
        file_put_contents($root.'/Copied.PHP', '<?php namespace Fight\\AccessControl\\Domain; final class Copied {}');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Copied shared namespace');
        assertProjectSourceBoundary($root);
    }

    private function fixtureRoot(string $name): string
    {
        return sys_get_temp_dir().'/project-yii-boundary-'.$name.'-'.bin2hex(random_bytes(5));
    }
}
