<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

/**
 * Class HttpApplicationTest
 */
#[CoversNothing]
final class HttpApplicationTest extends TestCase
{
    /**
     * Tests the configured home request
     */
    public function testAConfiguredRequestReachesTheHomeRoute(): void
    {
        $response = new ConfiguredApplicationFactory(dirname(__DIR__, 2))
            ->createApplication()
            ->handle(new ServerRequest('GET', 'http://localhost/'));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('home', $response->getHeaderLine('X-Route-Name'));
        self::assertStringContainsString('<h1>Hello, Fight Yii!</h1>', (string) $response->getBody());
    }
}
