<?php

declare(strict_types=1);

use App\Infrastructure\Container\CompletePlatformProvider;
use Fight\Common\Application\FileTransfer\Transport\FileTransport;
use Fight\Common\Application\FileStorage\FileStorage;
use Fight\Common\Application\Filesystem\Filesystem;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\HttpClient\Transport\HttpClient;
use Fight\Common\Application\Scheduler\Scheduler;
use Fight\Common\Domain\EventSourcing\EventStore;
use Fight\Common\Application\Repository\TransactionalUnitOfWork;
use Fight\Common\Application\Routing\UrlGenerator;
use Fight\Common\Application\Templating\TemplateEngine;
use Fight\Common\Application\Mail\Transport\MailTransport;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Fight\Common\Application\Messaging\Event\SynchronousEventDispatcher;
use Fight\Common\Application\Observability\AuditLog;
use Fight\Common\Application\Observability\MetricsCollector;
use Fight\Common\Application\Process\ProcessRunner;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use Yiisoft\Config\Config;
use Yiisoft\Config\ConfigPaths;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Db\Connection\ConnectionInterface;
use Fight\Common\Application\Socket\Publisher;
use Fight\Common\Application\Socket\PrivatePublisher;

require dirname(__DIR__, 2).'/vendor/autoload.php';
$root = dirname(__DIR__, 2);
$config = new Config(new ConfigPaths($root, 'config'), null, [], null);
if (($config->get('providers')['fight-common-platform'] ?? null) !== CompletePlatformProvider::class) {
    throw new RuntimeException('Complete platform provider must be selected by Yii configuration.');
}
$container = new Container(ContainerConfig::create()->withProviders([new CompletePlatformProvider()]));
foreach ([SynchronousCommandBus::class, AsynchronousCommandBus::class, SynchronousEventDispatcher::class, AsynchronousEventDispatcher::class, FileTransport::class, FileStorage::class, Filesystem::class, MailTransport::class, SmsTransport::class, AuditLog::class, MetricsCollector::class, ProcessRunner::class, PasswordHasher::class, PasswordValidator::class, HttpClient::class, ClientInterface::class, RequestFactoryInterface::class, ResponseFactoryInterface::class, StreamFactoryInterface::class, EventStore::class, Scheduler::class, TransactionalUnitOfWork::class, UrlGenerator::class, TemplateEngine::class, ValidatorInterface::class, Publisher::class, PrivatePublisher::class] as $service) {
    if (!$container->has($service) || !$container->get($service) instanceof $service) {
        throw new RuntimeException(sprintf('Default service %s is unavailable.', $service));
    }
}

$hash = $container->get(PasswordHasher::class)->hash('fight-yii');
if (!$container->get(PasswordValidator::class)->validate('fight-yii', $hash)) {
    throw new RuntimeException('Default password service must verify its own hash.');
}
$storage = $container->get(FileStorage::class);
$storage->putFile('profile/default.txt', 'stored');
if ($storage->getFileContents('profile/default.txt') !== 'stored') {
    throw new RuntimeException('Default Flysystem storage must round-trip a file.');
}
if ($container->get(UrlGenerator::class)->generate('home') !== '/') {
    throw new RuntimeException('Default named Yii route must generate.');
}
$container->get(Publisher::class)->push('https://fight.example/public', 'public');
$container->get(PrivatePublisher::class)->pushPrivate('https://fight.example/private', 'private');
$ran = false;
$scheduler = $container->get(Scheduler::class);
$scheduler->addJob('default-profile', '* * * * *', static function () use (&$ran): void { $ran = true; });
$scheduler->run();
if (!$ran) {
    throw new RuntimeException('Default scheduler must run a due callable job.');
}
$connection = $container->get(ConnectionInterface::class);
$container->get(TransactionalUnitOfWork::class)->commitTransactional(static function () use ($connection): void {
    $connection->createCommand('CREATE TABLE profile_receipt (value TEXT)')->execute();
    $connection->createCommand("INSERT INTO profile_receipt (value) VALUES ('committed')")->execute();
});
if ((int) $connection->createCommand('SELECT COUNT(*) FROM profile_receipt')->queryScalar() !== 1) {
    throw new RuntimeException('Default Yii transaction must commit.');
}
fwrite(STDOUT, "Complete Yii platform default services passed.\n");
