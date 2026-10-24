<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Container\HttpClientProvider;
use App\Adapter\Container\ProviderContext;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleClient;
use Fight\Common\Application\HttpClient\Transport\HttpClient;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;

/**
 * Class HttpClientProviderTest
 */
#[CoversClass(HttpClientProvider::class)]
final class HttpClientProviderTest extends TestCase
{
    /**
     * Tests configured HTTP transport and safe default selection
     */
    public function testItUsesAnExplicitHttpClientOrBuildsTheSafeDefault(): void
    {
        $configured = new GuzzleClient(new \GuzzleHttp\Client(['http_errors' => false]));
        $explicit = new HttpClientProvider(new ProviderContext('/project', ['app.http_client' => $configured]));
        $explicitContainer = new Container(ContainerConfig::create()->withProviders([$explicit]));
        self::assertSame($configured, $explicitContainer->get(HttpClient::class));
        self::assertSame([], $explicit->getExtensions());

        $default = new HttpClientProvider(new ProviderContext('/project', []));
        $defaultContainer = new Container(ContainerConfig::create()->withProviders([$default]));
        self::assertInstanceOf(GuzzleClient::class, $defaultContainer->get(HttpClient::class));
        self::assertSame([], $default->getExtensions());
    }
}
