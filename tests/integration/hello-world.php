<?php

declare(strict_types=1);

use App\Infrastructure\Container\ApplicationProvider;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Config\Config;
use Yiisoft\Config\ConfigPaths;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$root = dirname(__DIR__, 2);
$config = new Config(new ConfigPaths($root, 'config'), null, [], null);
$providers = $config->get('providers');

if (($providers['application'] ?? null) !== ApplicationProvider::class) {
    throw new RuntimeException('The application configuration must register the application service provider.');
}

$container = new Container(
    ContainerConfig::create()->withProviders([new ApplicationProvider($root)])
);

$response = $container->get(RequestHandlerInterface::class)->handle(new ServerRequest('GET', '/'));
$body = (string) $response->getBody();

if ($response->getStatusCode() !== 200 || !str_contains($body, 'Hello, Fight Yii!')) {
    throw new RuntimeException('GET / must render the independently specified hello-world response.');
}

fwrite(STDOUT, "Hello-world configuration and DI integration passed.\n");
