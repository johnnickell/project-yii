<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\CacheProvider;
use App\Adapter\Container\MailProvider;
use App\Adapter\Container\MessengerFallbackProvider;
use App\Adapter\Container\PersistenceProvider;
use App\Adapter\Container\SmsProvider;
use App\Adapter\Container\SynchronousMessagingProvider;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleClient;
use Fight\Common\Adapter\Messaging\Handler\CommandMessageHandler;
use Fight\Common\Adapter\ServiceContainer\Yii\PersistenceServiceProvider;
use Fight\Common\Application\Filesystem\Filesystem;
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
use Fight\Common\Domain\EventSourcing\EventMapper;
use Fight\Common\Domain\EventSourcing\EventStore;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Router\UrlMatcherInterface;
use Yiisoft\View\View;
use Yiisoft\View\ViewInterface;
use Yiisoft\View\WebView;
use Yiisoft\Yii\Http\Application;

/**
 * Class CapabilityProviderIsolationTest
 */
#[CoversNothing]
final class CapabilityProviderIsolationTest extends TestCase
{
    /**
     * Tests the selected HTTP path without communication integrations
     */
    public function testSelectedHttpProvidersHandleTheRoute(): void
    {
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 2))->createContainer(
            providerNames: [
                'routing-policy',
                'view-policy',
                'http-application',
                'fight-common-routing',
                'fight-common-view'
            ]
        );

        $response = $container->get(Application::class)->handle(new ServerRequest('GET', 'https://yii.example.test/'));

        self::assertSame(200, $response->getStatusCode());
        self::assertStringContainsString('Hello, Fight Yii!', (string) $response->getBody());
        self::assertFalse($container->has(MailTransport::class));
        self::assertFalse($container->has(SmsTransport::class));
        self::assertFalse($container->has(Publisher::class));
    }

    /**
     * Tests rejection of unknown capability selection
     */
    public function testUnknownSelectedProviderNameFailsClosed(): void
    {
        $factory = new ConfiguredApplicationFactory(dirname(__DIR__, 2));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('unknown-capability-policy');

        $factory->createContainer(providerNames: ['unknown-capability-policy']);
    }

    /**
     * Tests independent capability selection
     *
     * @param array $providerNames
     * @param class-string $expectedContract
     * @param class-string $unrelatedContract
     *
     * @phpstan-param list<string> $providerNames
     */
    #[DataProvider('independentlyComposableCapabilityGroups')]
    public function testEachCapabilityGroupResolvesItsPublicContract(
        array $providerNames,
        string $expectedContract,
        string $unrelatedContract
    ): void {
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 2))->createContainer(
            providerNames: $providerNames
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
            MailTransport::class
        ];
        yield 'view' => [
            ['view-policy', 'fight-common-view'],
            TemplateEngine::class,
            SmsTransport::class
        ];
        yield 'synchronous messaging' => [
            ['synchronous-messaging-policy', 'fight-common-messaging'],
            SynchronousCommandBus::class,
            MailTransport::class
        ];
        yield 'mail' => [
            ['mail-policy', 'fight-common-mail'],
            MailTransport::class,
            SmsTransport::class
        ];
        yield 'filesystem' => [
            ['files-policy', 'fight-common-filesystem'],
            Filesystem::class,
            MailTransport::class
        ];
        yield 'sms' => [
            ['sms-policy'],
            SmsTransport::class,
            MailTransport::class
        ];
        yield 'publication' => [
            ['publication-policy'],
            Publisher::class,
            MailTransport::class
        ];
        yield 'cache' => [
            ['cache-policy'],
            CacheItemPoolInterface::class,
            SmsTransport::class
        ];
        yield 'observability' => [
            ['observability-policy'],
            LoggerInterface::class,
            SmsTransport::class
        ];
    }

    /**
     * Tests routing without view or HTTP application selection
     */
    public function testSelectedRoutingProvidersBootIndependently(): void
    {
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 2))->createContainer(
            providerNames: ['routing-policy', 'fight-common-routing']
        );

        self::assertSame('/', $container->get(UrlGenerator::class)->generate('home'));
        self::assertFalse(
            $container->has(ViewInterface::class),
            sprintf('Routing selection must not register the %s or %s view contract.', View::class, WebView::class)
        );
        self::assertFalse($container->has(TemplateEngine::class));
        self::assertFalse($container->has(UrlMatcherInterface::class));
        self::assertFalse($container->has(MailTransport::class));
        self::assertFalse($container->has(CommandMessageHandler::class));
    }

    /**
     * Tests synchronous messaging without asynchronous fallback selection
     */
    public function testSelectedSynchronousMessagingExcludesAsyncFallbacks(): void
    {
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 2))->createContainer(
            providerNames: ['synchronous-messaging-policy', 'fight-common-messaging']
        );

        self::assertInstanceOf(SynchronousCommandBus::class, $container->get(SynchronousCommandBus::class));
        self::assertFalse($container->has(AsynchronousCommandBus::class));
        self::assertFalse($container->has(AsynchronousEventDispatcher::class));
    }

    /**
     * Tests Fight and PSR-18 requests through the selected HTTP transport
     */
    public function testSelectedHttpClientExposesFightAndPsr18Contracts(): void
    {
        $http = new GuzzleClient(new Client([
            'handler'     => HandlerStack::create(new MockHandler([
                new Response(202, [], 'fight-http'),
                new Response(204, ['X-Transport' => 'psr18'])
            ])),
            'http_errors' => false
        ]));
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 2))->createContainer(
            parameterOverrides: ['app.http_client' => $http],
            providerNames: ['http-client-policy', 'fight-common-http-client']
        );
        $request = new Request('GET', 'https://no-network.invalid/provider-isolation');

        self::assertInstanceOf(HttpClient::class, $container->get(HttpClient::class));
        self::assertInstanceOf(ClientInterface::class, $container->get(ClientInterface::class));
        self::assertSame(202, $container->get(HttpClient::class)->send($request)->getStatusCode());
        self::assertSame(
            'psr18',
            $container->get(ClientInterface::class)->sendRequest($request)->getHeaderLine('X-Transport')
        );
        self::assertFalse($container->has(UrlGenerator::class));
        self::assertFalse($container->has(MailTransport::class));
    }

    /**
     * Tests selected persistence transaction behavior without unrelated integrations
     */
    public function testSelectedPersistenceProvidersBootTheTransactionContract(): void
    {
        /** @var array<string, string|array{class: class-string, runtime: bool}> $providerMap */
        $providerMap = require dirname(__DIR__, 2).'/config/providers.php';
        self::assertSame(PersistenceProvider::class, $providerMap['persistence-policy']);
        self::assertSame(PersistenceServiceProvider::class, $providerMap['fight-common-persistence']);

        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 2))->createContainer(
            providerNames: ['cache-policy', 'observability-policy', 'persistence-policy', 'fight-common-persistence']
        );
        $connection = $container->get(ConnectionInterface::class);
        $connection->createCommand('CREATE TABLE selected_capability (value TEXT NOT NULL)')->execute();

        $result = $container->get(TransactionalUnitOfWork::class)->commitTransactional(
            static function () use ($connection): string {
                $connection->createCommand("INSERT INTO selected_capability VALUES ('committed')")->execute();

                return 'committed';
            }
        );

        self::assertSame('committed', $result);
        self::assertSame(
            ['committed'],
            $connection->createCommand('SELECT value FROM selected_capability')->queryColumn()
        );
        self::assertFalse($container->has(UrlGenerator::class));
        self::assertFalse($container->has(HttpClient::class));
        self::assertFalse($container->has(ClientInterface::class));
        self::assertFalse($container->has(MailTransport::class));
        self::assertFalse($container->has(CommandMessageHandler::class));
        self::assertSame($container->get(CacheInterface::class), $container->get(\Yiisoft\Cache\ArrayCache::class));
        self::assertSame($container->get(LoggerInterface::class), $container->get(\Yiisoft\Log\Logger::class));
    }

    /**
     * Tests the persistence-only capability boundary
     */
    public function testSelectedPersistenceProvidersExcludeEventSourcingContracts(): void
    {
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 2))->createContainer(
            providerNames: ['cache-policy', 'observability-policy', 'persistence-policy', 'fight-common-persistence']
        );

        self::assertInstanceOf(
            TransactionalUnitOfWork::class,
            $container->get(TransactionalUnitOfWork::class)
        );
        self::assertFalse(
            $container->has(EventStore::class),
            'EventStore must not be resolvable from the persistence-only container.'
        );
        self::assertFalse(
            $container->has(EventMapper::class),
            'EventMapper must not be resolvable from the persistence-only container.'
        );
    }

    /**
     * Tests the messaging-only capability boundary
     */
    public function testSelectedMessagingProvidersExcludeEventSourcingContracts(): void
    {
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 2))->createContainer(
            providerNames: ['synchronous-messaging-policy', 'fight-common-messaging']
        );

        self::assertInstanceOf(SynchronousCommandBus::class, $container->get(SynchronousCommandBus::class));
        self::assertFalse(
            $container->has(EventStore::class),
            'EventStore must not be resolvable from the messaging-only container.'
        );
        self::assertFalse(
            $container->has(EventMapper::class),
            'EventMapper must not be resolvable from the messaging-only container.'
        );
    }

    /**
     * Tests context-free provider construction
     */
    public function testProvidersWithoutContextCanBeInstantiatedWithoutArguments(): void
    {
        $providers = [
            new CacheProvider(),
            new MailProvider(),
            new PersistenceProvider(),
            new MessengerFallbackProvider(),
            new SmsProvider(),
            new SynchronousMessagingProvider()
        ];

        foreach ($providers as $provider) {
            self::assertInstanceOf(ServiceProviderInterface::class, $provider);
            self::assertNotEmpty($provider->getDefinitions(), $provider::class.' must define services');
            self::assertSame([], $provider->getExtensions());
        }
    }
}
