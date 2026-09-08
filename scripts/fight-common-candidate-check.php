<?php

declare(strict_types=1);

$root = dirname(__DIR__);
require_once $root.'/scripts/framework-support-profile.php';

$profile = frameworkSupportProfile($root);
frameworkSupportAssertPackageMatrix(frameworkSupportLockPackages($root.'/composer.lock'), $profile, 'composer.lock');

fwrite(STDOUT, "Fight Common candidate identity passed.\n");
