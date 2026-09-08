<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

final class DeploymentCredentialException extends RuntimeException implements ContainerExceptionInterface
{
}
