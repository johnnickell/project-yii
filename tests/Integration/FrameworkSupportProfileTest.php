<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use PHPUnit\Framework\TestCase;
use RuntimeException;

require_once __DIR__.'/../../scripts/framework-support-profile.php';

final class FrameworkSupportProfileTest extends TestCase
{
    public function test_real_manifest_lock_and_installed_package_matrix_match_the_closed_profile(): void
    {
        $root = dirname(__DIR__, 2);
        $profile = frameworkSupportProfile($root);

        self::assertSame(frameworkSupportSelectedDirectRuntimePackages(), $profile['selected_direct_runtime_packages']);
        frameworkSupportAssertPackageMatrix(frameworkSupportLockPackages($root.'/composer.lock'), $profile, 'composer.lock');
        frameworkSupportAssertPackageMatrix(
            frameworkSupportInstalledPackages($root.'/vendor/composer/installed.json'),
            $profile,
            'installed.json',
        );
    }

    public function test_candidate_repository_is_discovered_by_identity_not_position(): void
    {
        $root = dirname(__DIR__, 2);
        $manifest = frameworkSupportJson($root.'/composer.json');
        $manifest['repositories'] = array_reverse($manifest['repositories']);
        $fixtureRoot = sys_get_temp_dir().'/project-yii-profile-'.bin2hex(random_bytes(5));
        self::assertTrue(mkdir($fixtureRoot));

        try {
            file_put_contents($fixtureRoot.'/composer.json', json_encode($manifest, JSON_THROW_ON_ERROR));

            self::assertSame(frameworkSupportProfile($root), frameworkSupportProfile($fixtureRoot));
        } finally {
            unlink($fixtureRoot.'/composer.json');
            rmdir($fixtureRoot);
        }
    }

    public function test_manifest_fails_closed_for_a_missing_or_wrong_candidate_repository(): void
    {
        $root = dirname(__DIR__, 2);
        $manifest = frameworkSupportJson($root.'/composer.json');
        $manifest['repositories'] = [];

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Fight Common must resolve through its public VCS repository.');
        frameworkSupportAssertManifest($manifest);
    }

    public function test_manifest_fails_closed_for_a_wrong_repository_identity_and_candidate_constraint(): void
    {
        $root = dirname(__DIR__, 2);
        $manifest = frameworkSupportJson($root.'/composer.json');
        $replaced = false;
        foreach ($manifest['repositories'] as $position => $repository) {
            if (($repository['type'] ?? null) !== 'vcs'
                || ($repository['url'] ?? null) !== FRAMEWORK_SUPPORT_CANDIDATE_REPOSITORY) {
                continue;
            }
            $manifest['repositories'][$position]['url'] = 'https://github.com/example/not-fight-common';
            $replaced = true;
        }
        self::assertTrue($replaced, 'The fixture must replace the repository by identity.');

        try {
            frameworkSupportAssertManifest($manifest);
            self::fail('A wrong repository identity must fail closed.');
        } catch (RuntimeException $exception) {
            self::assertSame('Fight Common must resolve through its public VCS repository.', $exception->getMessage());
        }

        $manifest = frameworkSupportJson($root.'/composer.json');
        $manifest['require'][FRAMEWORK_SUPPORT_CANDIDATE_PACKAGE] = 'dev-develop';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Fight Common must use the authorized immutable candidate constraint.');
        frameworkSupportAssertManifest($manifest);
    }

    public function test_package_matrix_fails_closed_for_candidate_and_graph_drift(): void
    {
        $root = dirname(__DIR__, 2);
        $profile = frameworkSupportProfile($root);
        $packages = frameworkSupportLockPackages($root.'/composer.lock');
        $packages[FRAMEWORK_SUPPORT_CANDIDATE_PACKAGE]['source']['reference'] = str_repeat('0', 40);

        try {
            frameworkSupportAssertPackageMatrix($packages, $profile, 'fixture');
            self::fail('An incorrect candidate reference must fail closed.');
        } catch (RuntimeException $exception) {
            self::assertSame('Fight Common candidate identity is not exact in fixture.', $exception->getMessage());
        }

        $packages = frameworkSupportLockPackages($root.'/composer.lock');
        $packages['yiisoft/queue'] = ['name' => 'yiisoft/queue'];

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unselected runtime package is present in fixture: yiisoft/queue');
        frameworkSupportAssertPackageMatrix($packages, $profile, 'fixture');
    }
}
