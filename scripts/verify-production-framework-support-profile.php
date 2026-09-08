<?php

declare(strict_types=1);

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleClient;
use Fight\Common\Adapter\Messaging\Command\Sync\Routing\InMemoryCommandRouter;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\Auth\RequestService;
use Fight\Common\Application\Auth\Security\TokenDecoder;
use Fight\Common\Application\Auth\Security\TokenEncoder;
use Fight\Common\Application\Cache\Cache;
use Fight\Common\Application\FileStorage\FileStorage;
use Fight\Common\Application\FileTransfer\Transport\FileTransport;
use Fight\Common\Application\HttpClient\Transport\HttpClient;
use Fight\Common\Application\Mail\Message\MailFactory;
use Fight\Common\Application\Mail\Transport\MailTransport;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Command\CommandHandler;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Fight\Common\Application\Observability\AuditLog;
use Fight\Common\Application\Observability\MetricsCollector;
use Fight\Common\Application\Observability\HealthAggregator;
use Fight\Common\Application\Observability\HealthCheck;
use Fight\Common\Application\Repository\TransactionalUnitOfWork;
use Fight\Common\Application\Routing\UrlGenerator;
use Fight\Common\Application\Scheduler\Scheduler;
use Fight\Common\Application\Sms\Message\SmsMessage;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use Fight\Common\Application\Socket\PrivatePublisher;
use Fight\Common\Application\Socket\Publisher;
use Fight\Common\Domain\Messaging\Command\Command;
use Fight\Common\Domain\Messaging\Command\CommandMessage;
use Fight\Common\Domain\Messaging\Event\Event;
use Fight\Common\Domain\Messaging\Event\EventMessage;
use Fight\Common\Domain\Observability\AuditEntry;
use Fight\Common\Domain\Observability\HealthResult;
use Fight\Common\Domain\Observability\HealthStatus;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\MockHub;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;
use Yiisoft\Db\Cache\SchemaCache;
use Yiisoft\Db\Connection\ConnectionInterface;

$root = dirname(__DIR__);
require $root.'/vendor/autoload.php';
require $root.'/scripts/framework-support-profile.php';

final readonly class ProductionCommand implements Command
{
    public function __construct(private string $value)
    {
    }

    public static function fromArray(array $data): static
    {
        return new self($data['value']);
    }

    public function toArray(): array
    {
        return ['value' => $this->value];
    }
}

final readonly class ProductionEvent implements Event
{
    public function __construct(private string $value)
    {
    }

    public static function fromArray(array $data): static
    {
        return new self($data['value']);
    }

    public function toArray(): array
    {
        return ['value' => $this->value];
    }
}

final class ProductionCommandHandler implements CommandHandler
{
    /** @var list<string> */
    public array $values = [];

    public static function commandRegistration(): string
    {
        return ProductionCommand::class;
    }

    public function handle(CommandMessage $commandMessage): void
    {
        $this->values[] = $commandMessage->payload()->toArray()['value'];
    }
}

$profile = frameworkSupportProfile($root);
frameworkSupportAssertLane($root, $profile);
$factory = new ConfiguredApplicationFactory($root);
$runtime = sys_get_temp_dir().'/project-yii-production-'.bin2hex(random_bytes(5));
$container = $factory->createContainer([
    'app.hmac_identity' => 'production-yii-local-only',
    'app.hmac_private_hex' => str_repeat('a1', 32),
    'app.jwt_secret_hex' => str_repeat('b2', 32),
    'app.jwt_algorithm' => 'HS256',
    'app.storage_path' => $runtime.'/storage',
    'app.scheduler_path' => $runtime.'/scheduler',
    'app.http_client' => new GuzzleClient(new Client([
        'handler' => HandlerStack::create(new MockHandler([
            new GuzzleResponse(202, ['X-Fallback' => 'fight'], 'accepted'),
            new GuzzleResponse(204, ['X-Fallback' => 'psr18']),
        ])),
        'http_errors' => false,
    ])),
    'app.mercure_url' => 'https://mercure.example.test/production',
    'app.mercure_token' => 'production-local-token',
    'app.publication_result' => 'production-publication',
]);
$response = $container->get(Yiisoft\Yii\Http\Application::class)->handle(new ServerRequest('GET', '/'));
if ($response->getStatusCode() !== 200 || !str_contains((string) $response->getBody(), 'Hello, Fight Yii!')) {
    throw new RuntimeException('Production-installed Yii request journey failed.');
}
if ($container->get(UrlGenerator::class)->generate('home') !== '/') {
    throw new RuntimeException('Production-installed routing journey failed.');
}
$hash = $container->get(PasswordHasher::class)->hash('production-secret');
if (!$container->get(PasswordValidator::class)->validate('production-secret', $hash)) {
    throw new RuntimeException('Production-installed security journey failed.');
}
$signedRequest = $container->get(RequestService::class)->signRequest(
    new Request('POST', 'https://no-network.invalid/signed', [], 'production'),
);
if ($signedRequest->getHeaderLine('Credential') !== 'production-yii-local-only'
    || !preg_match('/^[a-f0-9]{64}$/', $signedRequest->getHeaderLine('Signature'))) {
    throw new RuntimeException('Production-installed HMAC request-signing journey failed.');
}
$token = $container->get(TokenEncoder::class)->encode(
    ['sub' => 'production-yii'],
    new DateTimeImmutable('+5 minutes'),
);
if (($container->get(TokenDecoder::class)->decode($token)['sub'] ?? null) !== 'production-yii') {
    throw new RuntimeException('Production-installed JWT round-trip journey failed.');
}
$nativeCache = $container->get(CacheInterface::class);
$nativeCache->set('production-native-cache', 'ready');
$loads = 0;
$fightCache = $container->get(Cache::class);
$loader = static function () use (&$loads): string {
    ++$loads;
    return 'ready';
};
if ($nativeCache->get('production-native-cache') !== 'ready'
    || $fightCache->read('production-fight-cache', $loader, 60) !== 'ready'
    || $fightCache->read('production-fight-cache', $loader, 60) !== 'ready'
    || $loads !== 1) {
    throw new RuntimeException('Production-installed cache composition journey failed.');
}
$container->get(LoggerInterface::class)->info('production-yii-composition');
$health = $container->get(HealthAggregator::class);
$health->addCheck(new class implements HealthCheck {
    public function check(): HealthResult
    {
        return new HealthResult($this->name(), HealthStatus::healthy());
    }

    public function name(): string
    {
        return 'production-yii-composition';
    }
});
if (!$health->report()->isHealthy() || $health->report()->results()[0]->name() !== 'production-yii-composition') {
    throw new RuntimeException('Production-installed observability composition journey failed.');
}
$httpRequest = new Request('GET', 'https://no-network.invalid/production');
if ($container->get(HttpClient::class)->send($httpRequest)->getStatusCode() !== 202
    || $container->get(ClientInterface::class)->sendRequest($httpRequest)->getStatusCode() !== 204) {
    throw new RuntimeException('Production-installed HTTP fallback journey failed.');
}
$handler = new ProductionCommandHandler();
$container->get(InMemoryCommandRouter::class)->registerHandler(ProductionCommand::class, $handler);
$container->get(SynchronousCommandBus::class)->execute(new ProductionCommand('production-handler'));
if ($handler->values !== ['production-handler']) {
    throw new RuntimeException('Production-installed synchronous messaging handler journey failed.');
}
$container->get(AsynchronousCommandBus::class)->execute(new ProductionCommand('production-command'));
$container->get(AsynchronousEventDispatcher::class)->trigger(new ProductionEvent('production-event'));
$envelopes = $container->get(InMemoryTransport::class)->getSent();
if (count($envelopes) !== 2
    || !$envelopes[0]->getMessage() instanceof CommandMessage
    || $envelopes[0]->getMessage()->payload()->toArray() !== ['value' => 'production-command']
    || !$envelopes[1]->getMessage() instanceof EventMessage
    || $envelopes[1]->getMessage()->payload()->toArray() !== ['value' => 'production-event']) {
    throw new RuntimeException('Production-installed Messenger envelope transport journey failed.');
}
$container->get(FileStorage::class)->putFile('production.txt', 'ready');
if ($container->get(FileStorage::class)->getFileContents('production.txt') !== 'ready') {
    throw new RuntimeException('Production-installed storage journey failed.');
}
$connection = $container->get(ConnectionInterface::class);
$container->get(TransactionalUnitOfWork::class)->commitTransactional(static function () use ($connection): void {
    $connection->createCommand('CREATE TABLE production_journey (value TEXT)')->execute();
    $connection->createCommand("INSERT INTO production_journey VALUES ('ready')")->execute();
});
if ($connection->createCommand('SELECT value FROM production_journey')->queryScalar() !== 'ready') {
    throw new RuntimeException('Production-installed persistence journey failed.');
}
$schemaCache = $container->get(SchemaCache::class);
$schemaCache->set('production-schema-metadata', ['column' => 'value']);
if (!$schemaCache->isEnabled() || $schemaCache->get('production-schema-metadata') !== ['column' => 'value']) {
    throw new RuntimeException('Production-installed schema-cache collaborator journey failed.');
}
$ran = false;
$container->get(Scheduler::class)->addJob('production-job', static fn (): bool => true, static function () use (&$ran): bool {
    $ran = true;

    return true;
});
$container->get(Scheduler::class)->run();
if (!$ran) {
    throw new RuntimeException('Production-installed scheduler journey failed.');
}
$mail = $container->get(MailFactory::class)->createMessage()->addFrom('from@example.test')->addTo('to@example.test')
    ->setSubject('production')->addContent('safe', 'text/plain');
$container->get(MailTransport::class)->send($mail);
$container->get(SmsTransport::class)->send(SmsMessage::create('+15555550100', '+15555550101')->setBody('safe'));
$container->get(FileTransport::class)->sendFile('ignored.txt', 'safe');
if ($container->get(FileTransport::class)->retrieveFileContents('ignored.txt') !== '') {
    throw new RuntimeException('Production-installed file-transfer fallback journey failed.');
}
$container->get(AuditLog::class)->record(AuditEntry::record('production', 'verified'));
$container->get(MetricsCollector::class)->increment('production.verified', ['safe' => 'true']);
$hub = $container->get(HubInterface::class);
if (!$hub instanceof MockHub
    || $hub->getUrl() !== 'https://mercure.example.test/production'
    || $hub->getProvider()->getJwt() !== 'production-local-token'
    || $hub->publish(new Update('production-topic', 'safe')) !== 'production-publication') {
    throw new RuntimeException('Production-installed Mercure policy fallback journey failed.');
}
$container->get(Publisher::class)->push('production-public-topic', 'safe');
$container->get(PrivatePublisher::class)->pushPrivate('production-private-topic', 'safe');

fwrite(STDOUT, "Production-installed Yii framework support profile passed.\n");
