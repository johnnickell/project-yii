<?php

declare(strict_types=1);

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use Fight\Common\Application\Auth\Security\PasswordHasher;
use Fight\Common\Application\Auth\Security\PasswordValidator;
use Fight\Common\Application\FileStorage\FileStorage;
use Fight\Common\Application\Repository\TransactionalUnitOfWork;
use Fight\Common\Application\Routing\UrlGenerator;
use Fight\Common\Application\Scheduler\Scheduler;
use Nyholm\Psr7\ServerRequest;
use Yiisoft\Db\Connection\ConnectionInterface;

$root = dirname(__DIR__);
require $root.'/vendor/autoload.php';
require $root.'/scripts/framework-support-profile.php';
$profile = frameworkSupportProfile($root);
frameworkSupportAssertLane($root, $profile);
$factory = new ConfiguredApplicationFactory($root);
$runtime = sys_get_temp_dir().'/project-yii-production-'.bin2hex(random_bytes(5));
$container = $factory->createContainer([
    'app.storage_path' => $runtime.'/storage',
    'app.scheduler_path' => $runtime.'/scheduler',
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
$ran = false;
$container->get(Scheduler::class)->addJob('production-job', static fn (): bool => true, static function () use (&$ran): bool {
    $ran = true;

    return true;
});
$container->get(Scheduler::class)->run();
if (!$ran) {
    throw new RuntimeException('Production-installed scheduler journey failed.');
}

fwrite(STDOUT, "Production-installed Yii framework support profile passed.\n");
