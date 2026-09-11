<?php

declare(strict_types=1);

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use Nyholm\Psr7\ServerRequest;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$root = dirname(__DIR__, 2);
$response = new ConfiguredApplicationFactory($root)
    ->createApplication()
    ->handle(new ServerRequest('GET', 'http://localhost/'));
if ($response->getStatusCode() !== 200 || !str_contains((string) $response->getBody(), 'Hello, Fight Yii!')) {
    throw new RuntimeException('The production HTTP composition did not boot the home route.');
}

$command = sprintf('%s %s list 2>&1', escapeshellarg(PHP_BINARY), escapeshellarg($root . '/scripts/console.php'));
exec($command, $output, $exitCode);
if ($exitCode !== 0 || !str_contains(implode("\n", $output), 'serve')) {
    throw new RuntimeException('The production Yii console composition did not boot the list command.');
}

fwrite(STDOUT, "Production no-dev HTTP and console boot passed.\n");
