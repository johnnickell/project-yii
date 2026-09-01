<?php

declare(strict_types=1);

use App\Infrastructure\Container\ApplicationProvider;
use App\Infrastructure\Container\CompletePlatformProvider;

return [
    'application' => ApplicationProvider::class,
    'fight-common-platform' => CompletePlatformProvider::class,
];
