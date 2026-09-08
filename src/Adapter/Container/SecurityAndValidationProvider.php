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
                self::requiredNonBlankString(
                    $this->context->parameters['app.hmac_identity'] ?? null,
                    'FIGHT_HMAC_PUBLIC',
                ),
                self::requiredHexSecret(
                    $this->context->parameters['app.hmac_private_hex'] ?? null,
                    'FIGHT_HMAC_PRIVATE',
                ),
            ),
            TokenEncoder::class => fn (): TokenEncoder => new JwtEncoder(
                self::requiredHexSecret(
                    $this->context->parameters['app.jwt_secret_hex'] ?? null,
                    'FIGHT_JWT_SECRET',
                ),
                $this->context->parameters['app.jwt_algorithm'],
            ),
            TokenDecoder::class => fn (): TokenDecoder => new JwtDecoder(
                self::requiredHexSecret(
                    $this->context->parameters['app.jwt_secret_hex'] ?? null,
                    'FIGHT_JWT_SECRET',
                ),
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

    private static function requiredNonBlankString(mixed $value, string $environmentVariable): string
    {
        if (!is_string($value) || trim($value) === '') {
            throw new DeploymentCredentialException(sprintf(
                'Security credential %s must be configured as a nonblank string.',
                $environmentVariable,
            ));
        }

        return $value;
    }

    private static function requiredHexSecret(mixed $value, string $environmentVariable): string
    {
        $value = self::requiredNonBlankString($value, $environmentVariable);

        if (preg_match('/\\A[0-9a-fA-F]{64}\\z/D', $value) !== 1) {
            throw new DeploymentCredentialException(sprintf(
                'Security credential %s must contain exactly 64 hexadecimal characters (32 bytes).',
                $environmentVariable,
            ));
        }

        return $value;
    }
}
