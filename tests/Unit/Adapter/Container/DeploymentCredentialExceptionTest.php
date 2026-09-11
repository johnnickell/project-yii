<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Container\DeploymentCredentialException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

#[CoversClass(DeploymentCredentialException::class)]
final class DeploymentCredentialExceptionTest extends TestCase
{
    public function test_it_is_a_container_runtime_exception(): void
    {
        $exception = new DeploymentCredentialException('missing deployment credential');

        self::assertInstanceOf(RuntimeException::class, $exception);
        self::assertInstanceOf(ContainerExceptionInterface::class, $exception);
        self::assertSame('missing deployment credential', $exception->getMessage());
    }
}
