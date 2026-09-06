<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\PersistenceProvider;
use Fight\Common\Adapter\Messaging\Handler\CommandMessageHandler;
use Fight\Common\Adapter\ServiceContainer\Yii\PersistenceServiceProvider;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleClient;
use Fight\Common\Application\HttpClient\Transport\HttpClient;
use Fight\Common\Application\Mail\Transport\MailTransport;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Fight\Common\Application\Repository\TransactionalUnitOfWork;
use Fight\Common\Application\Routing\UrlGenerator;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use Fight\Common\Application\Socket\Publisher;
use Fight\Common\Application\Templating\TemplateEngine;
use Fight\Common\Application\Filesystem\Filesystem;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\View\View;
use Yiisoft\View\ViewInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\Http\Application;

final class CapabilityProviderIsolationTest extends TestCase
{
    public function test_selected_http_application_providers_handle_the_route_without_communication_integrations(): void
    {
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(
            providerNames: [
                'routing-policy',
                'view-policy',
                'http-application',
                'fight-common-routing',
                'fight-common-view',
            ],
        );

        $response = $container->get(Application::class)->handle(new ServerRequest('GET', 'https://yii.example.test/'));

        self::assertSame(200, $response->getStatusCode());
        self::assertStringContainsString('Hello, Fight Yii!', (string) $response->getBody());
        self::assertFalse($container->has(MailTransport::class));
        self::assertFalse($container->has(SmsTransport::class));
        self::assertFalse($container->has(Publisher::class));
    }

    public function test_unknown_selected_provider_name_fails_closed(): void
    {
        $factory = new ConfiguredApplicationFactory(dirname(__DIR__, 2));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('unknown-capability-policy');

        $factory->createContainer(providerNames: ['unknown-capability-policy']);
    }

    /**
     * @param list<string> $providerNames
     * @param class-string $expectedContract
     * @param class-string $unrelatedContract
     */
    #[DataProvider('independentlyComposableCapabilityGroups')]
    public function test_each_capability_group_resolves_its_public_contract_without_an_unrelated_integration(
        array $providerNames,
        string $expectedContract,
        string $unrelatedContract,
    ): void {
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(
            providerNames: $providerNames,
        );

        self::assertInstanceOf($expectedContract, $container->get($expectedContract));
        self::assertFalse($container->has($unrelatedContract));
    }

    /**
     * @return iterable<string, array{list<string>, class-string, class-string}>
     */
    public static function independentlyComposableCapabilityGroups(): iterable
    {
        yield 'routing' => [
            ['routing-policy', 'fight-common-routing'],
            UrlGenerator::class,
            MailTransport::class,
        ];
        yield 'view' => [
            ['view-policy', 'fight-common-view'],
            TemplateEngine::class,
            SmsTransport::class,
        ];
        yield 'synchronous messaging' => [
            ['synchronous-messaging-policy', 'fight-common-messaging'],
            SynchronousCommandBus::class,
            MailTransport::class,
        ];
        yield 'mail' => [
            ['mail-policy', 'fight-common-mail'],
            MailTransport::class,
            SmsTransport::class,
        ];
        yield 'filesystem' => [
            ['files-policy', 'fight-common-filesystem'],
            Filesystem::class,
            MailTransport::class,
        ];
        yield 'sms' => [
            ['sms-policy'],
            SmsTransport::class,
            MailTransport::class,
        ];
        yield 'publication' => [
            ['publication-policy'],
            Publisher::class,
            MailTransport::class,
        ];
    }

    public function test_selected_routing_providers_boot_routing_without_view_or_http_application(): void
    {
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(
            providerNames: ['routing-policy', 'fight-common-routing'],
        );

        self::assertSame('/', $container->get(UrlGenerator::class)->generate('home'));
        self::assertFalse(
            $container->has(ViewInterface::class),
            sprintf('Routing selection must not register the %s or %s view contract.', View::class, WebView::class),
        );
        self::assertFalse($container->has(TemplateEngine::class));
        self::assertFalse($container->has(UrlMatcherInterface::class));
        self::assertFalse($container->has(MailTransport::class));
        self::assertFalse($container->has(CommandMessageHandler::class));
    }

    public function test_selected_synchronous_messaging_providers_exclude_messenger_fallback_contracts(): void
    {
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(
            providerNames: ['synchronous-messaging-policy', 'fight-common-messaging'],
        );

        self::assertInstanceOf(SynchronousCommandBus::class, $container->get(SynchronousCommandBus::class));
        self::assertFalse($container->has(AsynchronousCommandBus::class));
        self::assertFalse($container->has(AsynchronousEventDispatcher::class));
    }

    public function test_selected_http_client_providers_expose_the_fight_and_psr18_contracts_without_routing_or_mail(): void
    {
        $http = new GuzzleClient(new Client([
            'handler' => HandlerStack::create(new MockHandler([
                new Response(202, [], 'fight-http'),
                new Response(204, ['X-Transport' => 'psr18']),
            ])),
            'http_errors' => false,
        ]));
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(
            parameterOverrides: ['app.http_client' => $http],
            providerNames: ['http-client-policy', 'fight-common-http-client'],
        );
        $request = new Request('GET', 'https://no-network.invalid/provider-isolation');

        self::assertInstanceOf(HttpClient::class, $container->get(HttpClient::class));
        self::assertInstanceOf(ClientInterface::class, $container->get(ClientInterface::class));
        self::assertSame(202, $container->get(HttpClient::class)->send($request)->getStatusCode());
        self::assertSame('psr18', $container->get(ClientInterface::class)->sendRequest($request)->getHeaderLine('X-Transport'));
        self::assertFalse($container->has(UrlGenerator::class));
        self::assertFalse($container->has(MailTransport::class));
    }

    public function test_selected_persistence_providers_boot_the_transaction_contract_without_unrelated_public_contracts(): void
    {
        /** @var array<string, class-string> $providerMap */
        $providerMap = require dirname(__DIR__, 2).'/config/providers.php';
        self::assertSame(PersistenceProvider::class, $providerMap['persistence-policy']);
        self::assertSame(PersistenceServiceProvider::class, $providerMap['fight-common-persistence']);

        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(
            providerNames: ['persistence-policy', 'fight-common-persistence'],
        );
        $connection = $container->get(ConnectionInterface::class);
        $connection->createCommand('CREATE TABLE selected_capability (value TEXT NOT NULL)')->execute();

        $result = $container->get(TransactionalUnitOfWork::class)->commitTransactional(
            static function () use ($connection): string {
                $connection->createCommand("INSERT INTO selected_capability VALUES ('committed')")->execute();

                return 'committed';
            },
        );

        self::assertSame('committed', $result);
        self::assertSame(['committed'], $connection->createCommand('SELECT value FROM selected_capability')->queryColumn());
        self::assertFalse($container->has(UrlGenerator::class));
        self::assertFalse($container->has(HttpClient::class));
        self::assertFalse($container->has(ClientInterface::class));
        self::assertFalse($container->has(MailTransport::class));
        self::assertFalse($container->has(CommandMessageHandler::class));
    }
}
