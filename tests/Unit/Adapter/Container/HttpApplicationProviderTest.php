<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\HttpApplicationProvider;
use App\Adapter\Container\ProviderContext;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Yiisoft\Yii\Http\Application;

/**
 * Class HttpApplicationProviderTest
 */
#[CoversClass(HttpApplicationProvider::class)]
final class HttpApplicationProviderTest extends TestCase
{
    /**
     * Tests configured route dispatch and not-found responses
     */
    public function testItDispatchesTheConfiguredRouteAndReturnsNotFoundElsewhere(): void
    {
        $root = dirname(__DIR__, 4);
        $provider = new HttpApplicationProvider(new ProviderContext(
            $root,
            require $root.'/config/project-runtime.php'
        ));
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());

        $container = new ConfiguredApplicationFactory($root)->createContainer(providerNames: [
            'routing-policy', 'view-policy', 'http-application', 'fight-common-routing', 'fight-common-view'
        ]);
        $application = $container->get(Application::class);
        self::assertSame(200, $application->handle(new ServerRequest('GET', '/'))->getStatusCode());
        self::assertSame(500, $application->handle(new ServerRequest('GET', '/missing'))->getStatusCode());
    }
}
