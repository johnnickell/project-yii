<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Auth\Security\PhpPasswordHasher;
use Fight\Common\Adapter\Auth\Security\PhpPasswordValidator;
use Fight\Common\Adapter\Auth\Hmac\HmacRequestService;
use Fight\Common\Adapter\Auth\Security\JwtDecoder;
use Fight\Common\Adapter\Auth\Security\JwtEncoder;
use Fight\Common\Application\Auth\RequestService;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\Auth\Security\TokenDecoder;
use Fight\Common\Application\Auth\Security\TokenEncoder;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Validator\Validator;
use Yiisoft\Validator\ValidatorInterface;

final readonly class SecurityAndValidationProvider implements ServiceProviderInterface
{
    public function __construct(private ProviderContext $context)
    {
    }

    public function getDefinitions(): array
    {
        return [
            RequestService::class => fn (): RequestService => new HmacRequestService(
                $this->context->parameters['app.hmac_identity'],
                $this->context->parameters['app.hmac_private_hex'],
            ),
            TokenEncoder::class => fn (): TokenEncoder => new JwtEncoder(
                $this->context->parameters['app.jwt_secret_hex'],
                $this->context->parameters['app.jwt_algorithm'],
            ),
            TokenDecoder::class => fn (): TokenDecoder => new JwtDecoder(
                $this->context->parameters['app.jwt_secret_hex'],
                $this->context->parameters['app.jwt_algorithm'],
            ),
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
