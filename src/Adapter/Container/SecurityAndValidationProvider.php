<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Auth\Security\PhpPasswordHasher;
use Fight\Common\Adapter\Auth\Security\PhpPasswordValidator;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Validator\Validator;
use Yiisoft\Validator\ValidatorInterface;

final readonly class SecurityAndValidationProvider implements ServiceProviderInterface
{
    public function getDefinitions(): array
    {
        return [
            PasswordHasher::class => static fn (): PasswordHasher => new PhpPasswordHasher(PASSWORD_ARGON2ID),
            PasswordValidator::class => static fn (): PasswordValidator => new PhpPasswordValidator(PASSWORD_ARGON2ID),
            ValidatorInterface::class => Validator::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
