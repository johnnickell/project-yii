<?php

declare(strict_types=1);

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use Nyholm\Psr7\ServerRequest;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$root = dirname(__DIR__, 2);
$response = (new ConfiguredApplicationFactory($root))->createApplication()->handle(new ServerRequest('GET', '/'));
$body = (string) $response->getBody();

if ($response->getStatusCode() !== 200 || $response->getHeaderLine('X-Route-Name') !== 'home' || !str_contains($body, 'Hello, Fight Yii!')) {
    throw new RuntimeException('GET / must render the independently specified hello-world response.');
}

fwrite(STDOUT, "Hello-world configuration and DI integration passed.\n");
