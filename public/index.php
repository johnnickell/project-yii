<?php

declare(strict_types=1);

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Uri;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Config\Config;
use Yiisoft\Config\ConfigPaths;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Yii\Http\Application;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
$config = new Config(new ConfigPaths($root, 'config'), null, [], null);
$providers = array_map(
    static function (string $provider) use ($root): ServiceProviderInterface {
        return new $provider($root);
    },
    $config->get('providers')
);

$container = new Container(ContainerConfig::create()->withProviders($providers));
$uri = new Uri((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/'));
$request = new ServerRequest($_SERVER['REQUEST_METHOD'] ?? 'GET', $uri, getallheaders() ?: [], file_get_contents('php://input'));
$response = $container->get(Application::class)->handle($request);

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    header($name . ': ' . implode(', ', $values), false);
}

echo $response->getBody();
