<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\DeploymentCredentialException;
use App\Adapter\Container\ProviderContext;
use App\Adapter\Container\SecurityAndValidationProvider;
use Fight\Common\Application\Auth\RequestService;
use Fight\Common\Application\Auth\Security\TokenDecoder;
use Fight\Common\Application\Auth\Security\TokenEncoder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Yiisoft\Validator\ValidatorInterface;

#[CoversClass(SecurityAndValidationProvider::class)]
final class SecurityAndValidationProviderTest extends TestCase
{
    public function test_it_builds_security_and_validation_services_from_valid_credentials(): void
    {
        $root = dirname(__DIR__, 4);
        $parameters = [
            'app.hmac_identity' => 'identity',
            'app.hmac_private_hex' => str_repeat('ab', 32),
            'app.jwt_secret_hex' => str_repeat('cd', 32),
        ];
        $provider = new SecurityAndValidationProvider(new ProviderContext($root, $parameters));
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());

        $container = new ConfiguredApplicationFactory($root)->createContainer($parameters, ['security-and-validation']);
        self::assertInstanceOf(RequestService::class, $container->get(RequestService::class));
        self::assertInstanceOf(TokenEncoder::class, $container->get(TokenEncoder::class));
        self::assertInstanceOf(TokenDecoder::class, $container->get(TokenDecoder::class));
        self::assertInstanceOf(ValidatorInterface::class, $container->get(ValidatorInterface::class));
    }

    /** @param array<string, mixed> $overrides */
    #[DataProvider('invalidCredentials')]
    public function test_it_rejects_missing_blank_non_string_and_invalid_hex_credentials(
        string $service,
        array $overrides,
    ): void {
        $root = dirname(__DIR__, 4);
        $parameters = array_replace([
            'app.hmac_identity' => 'identity',
            'app.hmac_private_hex' => str_repeat('ab', 32),
            'app.jwt_secret_hex' => str_repeat('cd', 32),
        ], $overrides);
        $container = new ConfiguredApplicationFactory($root)->createContainer($parameters, ['security-and-validation']);

        $this->expectException(DeploymentCredentialException::class);
        $container->get($service);
    }

    /** @return iterable<string, array{class-string, array<string, mixed>}> */
    public static function invalidCredentials(): iterable
    {
        yield 'missing identity' => [RequestService::class, ['app.hmac_identity' => null]];
        yield 'non-string identity' => [RequestService::class, ['app.hmac_identity' => []]];
        yield 'blank identity' => [RequestService::class, ['app.hmac_identity' => '   ']];
        yield 'missing HMAC secret' => [RequestService::class, ['app.hmac_private_hex' => null]];
        yield 'invalid HMAC hex' => [RequestService::class, ['app.hmac_private_hex' => str_repeat('g', 64)]];
        yield 'invalid JWT hex' => [TokenEncoder::class, ['app.jwt_secret_hex' => str_repeat('a', 62)]];
    }
}
