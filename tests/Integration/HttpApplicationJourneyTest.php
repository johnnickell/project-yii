<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\Routing\UrlGenerator;
use Fight\Common\Application\Templating\TemplateEngine;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\View\View;
use Yiisoft\View\ViewInterface;
use Yiisoft\View\WebView;

final class HttpApplicationJourneyTest extends TestCase
{
    public function test_the_same_configured_factory_as_the_web_entrypoint_handles_the_request_lifecycle(): void
    {
        $factory = new ConfiguredApplicationFactory(dirname(__DIR__, 2));
        $response = $factory->createApplication()->handle(new ServerRequest('GET', 'https://yii.example.test/'));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('text/html; charset=utf-8', $response->getHeaderLine('Content-Type'));
        self::assertSame('home', $response->getHeaderLine('X-Route-Name'));
        self::assertStringContainsString('<h1>Hello, Fight Yii!</h1>', (string) $response->getBody());
    }

    public function test_routing_twig_security_and_validation_have_independent_expected_outcomes(): void
    {
        $factory = new ConfiguredApplicationFactory(dirname(__DIR__, 2));
        $parameters = [
            'app.route_path' => '/configured-hello',
            'app.route_name' => 'configured-hello',
            'app.templates_path' => 'tests/Fixture/Http/configured-templates',
        ];
        $application = $factory->createApplication($parameters);

        $response = $application->handle(new ServerRequest('GET', 'https://yii.example.test/configured-hello'));
        self::assertSame(200, $response->getStatusCode());
        self::assertSame('configured-hello', $response->getHeaderLine('X-Route-Name'));
        self::assertStringContainsString('<h1>Configured Yii request</h1>', (string) $response->getBody());

        $outsideConfiguredRoute = $factory
            ->createApplication($parameters)
            ->handle(new ServerRequest('GET', 'https://yii.example.test/'));
        self::assertSame(404, $outsideConfiguredRoute->getStatusCode());

        $container = $factory->createContainer($parameters);

        self::assertInstanceOf(View::class, $container->get(ViewInterface::class));
        self::assertInstanceOf(WebView::class, $container->get(WebView::class));
        self::assertSame('/configured-hello', $container->get(UrlGenerator::class)->generate('configured-hello'));
        self::assertStringContainsString(
            'Configured Yii request',
            $container->get(TemplateEngine::class)->render('home.twig'),
        );
        $hash = $container->get(PasswordHasher::class)->hash('starter-secret');
        self::assertTrue($container->get(PasswordValidator::class)->validate('starter-secret', $hash));
        self::assertFalse($container->get(PasswordValidator::class)->validate('wrong-secret', $hash));
        self::assertTrue($container->get(ValidatorInterface::class)->validate('invalid-address', [new Email()])->isValid() === false);
        self::assertTrue($container->get(ValidatorInterface::class)->validate('starter@example.test', [new Email()])->isValid());
    }
}
