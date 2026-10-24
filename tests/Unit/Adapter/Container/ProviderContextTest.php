<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Container\ProviderContext;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Class ProviderContextTest
 */
#[CoversClass(ProviderContext::class)]
final class ProviderContextTest extends TestCase
{
    /**
     * Tests relative and absolute runtime paths
     */
    public function testItResolvesRelativePathsAndPreservesAbsolutePaths(): void
    {
        $context = new ProviderContext('/project', ['setting' => 'value']);

        self::assertSame('/project', $context->root);
        self::assertSame(['setting' => 'value'], $context->parameters);
        self::assertSame('/project/var/cache', $context->absolutePath('var/cache'));
        self::assertSame('/tmp/cache', $context->absolutePath('/tmp/cache'));
    }
}
