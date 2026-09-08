<?php

declare(strict_types=1);

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

use Nyholm\Psr7\ServerRequest;
use Nyholm\Psr7\Uri;
use App\Adapter\Bootstrap\ConfiguredApplicationFactory;

require dirname(__DIR__) . '/vendor/autoload.php';

$root = dirname(__DIR__);
$application = (new ConfiguredApplicationFactory($root))->createApplication();
$uri = new Uri((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/'));
$request = new ServerRequest($_SERVER['REQUEST_METHOD'] ?? 'GET', $uri, getallheaders() ?: [], file_get_contents('php://input'));
$response = $application->handle($request);

http_response_code($response->getStatusCode());
foreach ($response->getHeaders() as $name => $values) {
    header($name . ': ' . implode(', ', $values), false);
}

echo $response->getBody();
