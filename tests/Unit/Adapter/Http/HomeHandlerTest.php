<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Http;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Http\HomeHandler;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HomeHandler::class)]
final class HomeHandlerTest extends TestCase
{
    public function test_it_returns_the_rendered_home_response(): void
    {
        $handler = new ConfiguredApplicationFactory(dirname(__DIR__, 4))->createContainer()->get(HomeHandler::class);
        $response = $handler->handle(new ServerRequest('GET', '/'));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('text/html; charset=utf-8', $response->getHeaderLine('Content-Type'));
        self::assertSame('home', $response->getHeaderLine('X-Route-Name'));
        self::assertStringContainsString('Hello, Fight Yii!', (string) $response->getBody());
    }
}
