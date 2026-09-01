<?php

declare(strict_types=1);

namespace App\Infrastructure\Container;

use Fight\Common\Adapter\FileTransfer\Null\NullFileTransport;
use Fight\Common\Adapter\EventSourcing\InMemory\InMemoryEventStore;
use Fight\Common\Adapter\Persistence\Yii\YiiTransactionalUnitOfWork;
use Fight\Common\Adapter\Routing\Yii\YiiUrlGenerator;
use Fight\Common\Adapter\Templating\Yii\YiiTemplateEngine;
use Fight\Common\Adapter\Socket\MercureHubPublisher;
use Fight\Common\Adapter\Socket\PrivateMercureHubPublisher;
use Fight\Common\Adapter\FileStorage\FlysystemStorage;
use Fight\Common\Adapter\Filesystem\Symfony\SymfonyFilesystem;
use Fight\Common\Adapter\Auth\Security\PhpPasswordHasher;
use Fight\Common\Adapter\Auth\Security\PhpPasswordValidator;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleClient;
use Fight\Common\Adapter\HttpClient\Psr18\Psr18Client;
use Fight\Common\Adapter\Mail\Symfony\SymfonyMailFactory;
use Fight\Common\Adapter\Mail\Symfony\SymfonyMailTransport;
use Fight\Common\Adapter\Messaging\Command\Async\MessengerCommandBus;
use Fight\Common\Adapter\Messaging\Command\Sync\Routing\InMemoryCommandRouter;
use Fight\Common\Adapter\Messaging\Command\Sync\Routing\CommandRouter;
use Fight\Common\Adapter\Messaging\Command\Sync\RoutingCommandBus;
use Fight\Common\Adapter\Messaging\Event\Async\MessengerEventDispatcher;
use Fight\Common\Adapter\Messaging\Event\Sync\SimpleEventDispatcher;
use Fight\Common\Adapter\Observability\Audit\NullAuditLog;
use Fight\Common\Adapter\Observability\Metrics\NullMetricsCollector;
use Fight\Common\Adapter\Process\Symfony\SymfonyProcessRunner;
use Fight\Common\Adapter\Sms\Null\NullSmsTransport;
use Fight\Common\Application\FileTransfer\Transport\FileTransport;
use Fight\Common\Application\FileStorage\FileStorage;
use Fight\Common\Application\Filesystem\Filesystem;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\HttpClient\Transport\HttpClient;
use Fight\Common\Application\Mail\Transport\MailTransport;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Fight\Common\Application\Messaging\Event\SynchronousEventDispatcher;
use Fight\Common\Application\Observability\AuditLog;
use Fight\Common\Application\Observability\MetricsCollector;
use Fight\Common\Application\Process\ProcessRunner;
use Fight\Common\Application\Repository\TransactionalUnitOfWork;
use Fight\Common\Application\Routing\UrlGenerator;
use Fight\Common\Application\Templating\TemplateEngine;
use Fight\Common\Application\Socket\Publisher;
use Fight\Common\Application\Socket\PrivatePublisher;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use Fight\Common\Application\Scheduler\Scheduler;
use Fight\Common\Domain\EventSourcing\EventMapper;
use Fight\Common\Domain\EventSourcing\EventStore;
use Fight\Common\Domain\Value\DateTime\Timezone;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use GuzzleHttp\Client;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Cache\ArrayCache;
use Yiisoft\Db\Cache\SchemaCache;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Sqlite\Connection;
use Yiisoft\Db\Sqlite\Driver;
use Yiisoft\Router\FastRoute\UrlGenerator as NativeUrlGenerator;
use Yiisoft\Router\Route;
use Yiisoft\Router\RouteCollection;
use Yiisoft\Router\RouteCollector;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\View\View;
use Yiisoft\View\ViewInterface;
use Yiisoft\Validator\Validator;
use Yiisoft\Validator\ValidatorInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\MockHub;
use Symfony\Component\Mercure\Jwt\StaticTokenProvider;

/** Default safe adapters; application configuration replaces transport/secrets policy. */
final class CompletePlatformProvider implements ServiceProviderInterface
{
    public function getDefinitions(): array
    {
        return [
            InMemoryCommandRouter::class => InMemoryCommandRouter::class,
            CommandRouter::class => InMemoryCommandRouter::class,
            SynchronousCommandBus::class => RoutingCommandBus::class,
            SynchronousEventDispatcher::class => SimpleEventDispatcher::class,
            SenderInterface::class => InMemoryTransport::class,
            AsynchronousCommandBus::class => MessengerCommandBus::class,
            AsynchronousEventDispatcher::class => MessengerEventDispatcher::class,
            FileTransport::class => NullFileTransport::class,
            MailerInterface::class => static fn (): MailerInterface => new Mailer(Transport::fromDsn('null://null')),
            MailTransport::class => SymfonyMailTransport::class,
            \Fight\Common\Application\Mail\Message\MailFactory::class => SymfonyMailFactory::class,
            SmsTransport::class => NullSmsTransport::class,
            AuditLog::class => NullAuditLog::class,
            MetricsCollector::class => NullMetricsCollector::class,
            ProcessRunner::class => SymfonyProcessRunner::class,
            PasswordHasher::class => static fn (): PasswordHasher => new PhpPasswordHasher(PASSWORD_ARGON2ID),
            PasswordValidator::class => static fn (): PasswordValidator => new PhpPasswordValidator(PASSWORD_ARGON2ID),
            HttpClient::class => static fn (): HttpClient => new GuzzleClient(new Client()),
            ClientInterface::class => Psr18Client::class,
            RequestFactoryInterface::class => static fn (): RequestFactoryInterface => new Psr17Factory(),
            ResponseFactoryInterface::class => static fn (): ResponseFactoryInterface => new Psr17Factory(),
            StreamFactoryInterface::class => static fn (): StreamFactoryInterface => new Psr17Factory(),
            FilesystemOperator::class => static fn (): FilesystemOperator => new Flysystem(
                new LocalFilesystemAdapter(sys_get_temp_dir().'/project-yii-storage')
            ),
            FileStorage::class => FlysystemStorage::class,
            Filesystem::class => SymfonyFilesystem::class,
            EventMapper::class => static fn (): EventMapper => new EventMapper([]),
            EventStore::class => InMemoryEventStore::class,
            Scheduler::class => static fn (ProcessRunner $runner): Scheduler => Scheduler::withProcessRunner(
                new Timezone('UTC'), sys_get_temp_dir(), $runner
            ),
            ConnectionInterface::class => static fn (): ConnectionInterface => new Connection(
                new Driver('sqlite::memory:'), new SchemaCache(new ArrayCache())
            ),
            TransactionalUnitOfWork::class => YiiTransactionalUnitOfWork::class,
            UrlGeneratorInterface::class => static function (): UrlGeneratorInterface {
                $collector = new RouteCollector();
                $collector->addRoute(Route::get('/')->name('home'));

                return new NativeUrlGenerator(new RouteCollection($collector));
            },
            UrlGenerator::class => YiiUrlGenerator::class,
            ViewInterface::class => View::class,
            TemplateEngine::class => static fn (ViewInterface $view): TemplateEngine => new YiiTemplateEngine(
                $view, dirname(__DIR__, 3).'/resources/views'
            ),
            ValidatorInterface::class => Validator::class,
            HubInterface::class => static fn (): HubInterface => new MockHub(
                'http://localhost/.well-known/mercure', new StaticTokenProvider('project-yii-local'), static fn (): string => 'published'
            ),
            Publisher::class => MercureHubPublisher::class,
            PrivatePublisher::class => PrivateMercureHubPublisher::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
