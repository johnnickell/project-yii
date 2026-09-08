<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use DateTimeImmutable;
use Fight\Common\Application\Auth\RequestService;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\Auth\Security\TokenDecoder;
use Fight\Common\Application\Auth\Security\TokenEncoder;
use Nyholm\Psr7\Request;
use PHPUnit\Framework\TestCase;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\ValidatorInterface;

final class SecurityAndValidationJourneyTest extends TestCase
{
    public function test_booted_security_services_use_project_owned_runtime_configuration(): void
    {
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer([
            'app.hmac_identity' => 'configured-yii-client',
            'app.hmac_private_hex' => str_repeat('ab', 32),
            'app.jwt_secret_hex' => str_repeat('cd', 32),
            'app.jwt_algorithm' => 'HS256',
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
        $claims = $container->get(TokenDecoder::class)->decode($token);
        self::assertSame('yii-starter', $claims['sub']);
        self::assertSame('security', $claims['capability']);

        $hash = $container->get(PasswordHasher::class)->hash('starter-secret');
        self::assertTrue($container->get(PasswordValidator::class)->validate('starter-secret', $hash));
        self::assertFalse($container->get(PasswordValidator::class)->validate('wrong-secret', $hash));
        self::assertFalse($container->get(ValidatorInterface::class)->validate('invalid', [new Email()])->isValid());
        self::assertTrue($container->get(ValidatorInterface::class)->validate('starter@example.test', [new Email()])->isValid());
    }
}
