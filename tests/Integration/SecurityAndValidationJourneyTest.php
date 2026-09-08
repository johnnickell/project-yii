<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\DeploymentCredentialException;
use DateTimeImmutable;
use Fight\Common\Application\Auth\RequestService;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\Auth\Security\TokenDecoder;
use Fight\Common\Application\Auth\Security\TokenEncoder;
use Nyholm\Psr7\Request;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\ValidatorInterface;

final class SecurityAndValidationJourneyTest extends TestCase
{
    public function test_deployment_credential_exception_autoloads_independently(): void
    {
        self::assertTrue(class_exists(DeploymentCredentialException::class));
    }

    #[DataProvider('invalidHmacCredentialValues')]
    public function test_request_service_fails_closed_for_invalid_hmac_credentials(
        string $parameter,
        mixed $value,
        string $environmentVariable,
    ): void {
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(
            self::securityRuntimeOverrides([$parameter => $value]),
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage($environmentVariable);

        $container->get(RequestService::class);
    }

    /**
     * @return iterable<string, array{string, mixed, string}>
     */
    public static function invalidHmacCredentialValues(): iterable
    {
        yield 'missing identity' => ['app.hmac_identity', null, 'FIGHT_HMAC_PUBLIC'];
        yield 'non-string identity' => ['app.hmac_identity', [], 'FIGHT_HMAC_PUBLIC'];
        yield 'blank identity' => ['app.hmac_identity', '   ', 'FIGHT_HMAC_PUBLIC'];
        yield 'missing private key' => ['app.hmac_private_hex', null, 'FIGHT_HMAC_PRIVATE'];
        yield 'non-string private key' => ['app.hmac_private_hex', [], 'FIGHT_HMAC_PRIVATE'];
        yield 'blank private key' => ['app.hmac_private_hex', '   ', 'FIGHT_HMAC_PRIVATE'];
        yield 'non-hex private key' => ['app.hmac_private_hex', str_repeat('g', 64), 'FIGHT_HMAC_PRIVATE'];
        yield 'undersized private key' => ['app.hmac_private_hex', str_repeat('a', 62), 'FIGHT_HMAC_PRIVATE'];
    }

    #[DataProvider('invalidJwtSecretValues')]
    public function test_token_services_fail_closed_for_invalid_jwt_secrets(
        string $service,
        mixed $secret,
    ): void {
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(
            self::securityRuntimeOverrides(['app.jwt_secret_hex' => $secret]),
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('FIGHT_JWT_SECRET');

        $container->get($service);
    }

    /**
     * @return iterable<string, array{class-string, mixed}>
     */
    public static function invalidJwtSecretValues(): iterable
    {
        foreach ([TokenEncoder::class, TokenDecoder::class] as $service) {
            yield $service.' missing secret' => [$service, null];
            yield $service.' non-string secret' => [$service, []];
            yield $service.' blank secret' => [$service, '   '];
            yield $service.' non-hex secret' => [$service, str_repeat('g', 64)];
            yield $service.' undersized secret' => [$service, str_repeat('a', 62)];
        }
    }

    public function test_security_credentials_are_validated_only_when_their_services_are_resolved(): void
    {
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(
            self::securityRuntimeOverrides([
                'app.hmac_identity' => null,
                'app.hmac_private_hex' => null,
                'app.jwt_secret_hex' => null,
            ]),
        );

        self::assertInstanceOf(ValidatorInterface::class, $container->get(ValidatorInterface::class));
    }

    public function test_booted_security_services_use_project_owned_runtime_configuration(): void
    {
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer([
            'app.hmac_identity' => 'configured-yii-client',
            'app.hmac_private_hex' => str_repeat('ab', 32),
            'app.jwt_secret_hex' => str_repeat('cd', 32),
        ]);

        $body = '{"action":"prove-yii-composition"}';
        $signed = $container->get(RequestService::class)->signRequest(
            new Request('POST', 'https://yii.example.test/signed?b=2&a=1', [], $body),
        );
        self::assertSame('HMAC-SHA256', $signed->getHeaderLine('Authorization'));
        self::assertSame('configured-yii-client', $signed->getHeaderLine('Credential'));
        self::assertMatchesRegularExpression('/^[a-f0-9]{64}$/', $signed->getHeaderLine('Signature'));
        self::assertNotSame('', $signed->getHeaderLine('X-Nonce'));
        self::assertSame(hash('sha256', $body), $signed->getHeaderLine('X-Content-SHA256'));

        $token = $container->get(TokenEncoder::class)->encode(
            ['sub' => 'yii-starter', 'capability' => 'security'],
            new DateTimeImmutable('+5 minutes'),
        );
        $tokenHeader = json_decode(
            base64_decode(strtr(explode('.', $token, 2)[0], '-_', '+/'), true),
            true,
            512,
            JSON_THROW_ON_ERROR,
        );
        self::assertSame('HS256', $tokenHeader['alg']);
        $claims = $container->get(TokenDecoder::class)->decode($token);
        self::assertSame('yii-starter', $claims['sub']);
        self::assertSame('security', $claims['capability']);

        $hash = $container->get(PasswordHasher::class)->hash('starter-secret');
        self::assertTrue($container->get(PasswordValidator::class)->validate('starter-secret', $hash));
        self::assertFalse($container->get(PasswordValidator::class)->validate('wrong-secret', $hash));
        self::assertFalse($container->get(ValidatorInterface::class)->validate('invalid', [new Email()])->isValid());
        self::assertTrue($container->get(ValidatorInterface::class)->validate('starter@example.test', [new Email()])->isValid());
    }

    /**
     * @param array<string, mixed> $overrides
     *
     * @return array<string, mixed>
     */
    private static function securityRuntimeOverrides(array $overrides = []): array
    {
        return array_replace([
            'app.hmac_identity' => 'configured-yii-client',
            'app.hmac_private_hex' => str_repeat('ab', 32),
            'app.jwt_secret_hex' => str_repeat('cd', 32),
        ], $overrides);
    }
}
