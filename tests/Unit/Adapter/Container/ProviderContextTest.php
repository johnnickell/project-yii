<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Container\ProviderContext;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ProviderContext::class)]
final class ProviderContextTest extends TestCase
{
    public function test_it_resolves_relative_paths_and_preserves_absolute_paths(): void
    {
        $context = new ProviderContext('/project', ['setting' => 'value']);

        self::assertSame('/project', $context->root);
        self::assertSame(['setting' => 'value'], $context->parameters);
        self::assertSame('/project/var/cache', $context->absolutePath('var/cache'));
        self::assertSame('/tmp/cache', $context->absolutePath('/tmp/cache'));
    }
}
