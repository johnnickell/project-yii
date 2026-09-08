<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\Routing\UrlGenerator;
use Fight\Common\Application\Templating\TemplateEngine;
use Fight\Common\Adapter\Http\Psr17\JSendResponseFactory;
use Fight\Common\Application\Http\JSend\JSendEnvelope;
use Fight\Common\Domain\Exception\ValidationException;
use Fight\Common\Domain\Type\Arrayable;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Yiisoft\Validator\Rule\Email;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\View\View;
use Yiisoft\View\ViewInterface;
use Yiisoft\View\WebView;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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

    public function test_shared_json_and_jsend_middleware_execute_through_the_yii_router(): void
    {
        $factory = new ConfiguredApplicationFactory(dirname(__DIR__, 2));
        $successAction = static function (
            ServerRequestInterface $request,
            JSendResponseFactory $responses,
        ): ResponseInterface {
            $data = $request->getParsedBody();

            return $responses->fromEnvelope(
                JSendEnvelope::success(new readonly class ($data) implements Arrayable {
                    /** @param array<string, mixed> $data */
                    public function __construct(private array $data) {}
                    public function toArray(): array
                    {
                        return $this->data;
                    }
                }),
                201,
                ['X-Route-Outcome' => 'created'],
            );
        };
        $parameters = [
            'app.route_path' => '/json',
            'app.route_name' => 'json',
            'app.route_methods' => ['POST'],
            'app.route_action' => $successAction,
        ];

        $response = $factory->createApplication($parameters)->handle(
            (new ServerRequest('POST', 'https://yii.example.test/json'))
                ->withHeader('Content-Type', 'application/json; charset=utf-8')
                ->withBody((new \Nyholm\Psr7\Factory\Psr17Factory())->createStream('{"role":"editor"}')),
        );
        self::assertSame(201, $response->getStatusCode());
        self::assertSame('application/json', $response->getHeaderLine('Content-Type'));
        self::assertSame('created', $response->getHeaderLine('X-Route-Outcome'));
        self::assertSame(
            ['status' => 'success', 'data' => ['role' => 'editor']],
            json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR),
        );

        $validation = $factory->createApplication([
            ...$parameters,
            'app.route_action' => static fn (): never => throw new ValidationException([
                'role' => ['Role is required'],
            ]),
        ])->handle(new ServerRequest('POST', 'https://yii.example.test/json'));
        self::assertSame(400, $validation->getStatusCode());
        self::assertSame(
            ['status' => 'fail', 'data' => ['role' => ['Role is required']]],
            json_decode((string) $validation->getBody(), true, flags: JSON_THROW_ON_ERROR),
        );

        $failure = $factory->createApplication([
            ...$parameters,
            'app.route_action' => static fn (): never => throw new \RuntimeException('route failed'),
        ])->handle(new ServerRequest('POST', 'https://yii.example.test/json'));
        self::assertSame(500, $failure->getStatusCode());
        self::assertSame(
            ['status' => 'error', 'message' => 'route failed'],
            json_decode((string) $failure->getBody(), true, flags: JSON_THROW_ON_ERROR),
        );

        $malformed = $factory->createApplication($parameters)->handle(
            (new ServerRequest('POST', 'https://yii.example.test/json'))
                ->withHeader('Content-Type', 'application/json')
                ->withBody((new \Nyholm\Psr7\Factory\Psr17Factory())->createStream('{not-json')),
        );
        self::assertSame(500, $malformed->getStatusCode());
        self::assertSame('application/json', $malformed->getHeaderLine('Content-Type'));
        self::assertSame(
            ['status' => 'error', 'message' => 'Syntax error'],
            json_decode((string) $malformed->getBody(), true, flags: JSON_THROW_ON_ERROR),
        );
    }
}
