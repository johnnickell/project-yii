<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Container\DeploymentCredentialException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

/**
 * Class DeploymentCredentialExceptionTest
 */
#[CoversClass(DeploymentCredentialException::class)]
final class DeploymentCredentialExceptionTest extends TestCase
{
    /**
     * Tests the credential failure contract
     */
    public function testItIsAContainerRuntimeException(): void
    {
        $exception = new DeploymentCredentialException('missing deployment credential');

        self::assertInstanceOf(RuntimeException::class, $exception);
        self::assertInstanceOf(ContainerExceptionInterface::class, $exception);
        self::assertSame('missing deployment credential', $exception->getMessage());
    }
}
