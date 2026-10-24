<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

/**
 * Class DeploymentCredentialException
 */
final class DeploymentCredentialException extends RuntimeException implements ContainerExceptionInterface
{
}
