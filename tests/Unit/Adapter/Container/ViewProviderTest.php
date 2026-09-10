<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\ProviderContext;
use App\Adapter\Container\ViewProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Yiisoft\View\WebView;

#[CoversClass(ViewProvider::class)]
final class ViewProviderTest extends TestCase
{
    public function test_it_renders_twig_through_the_yii_web_view(): void
    {
        $root = dirname(__DIR__, 4);
        $provider = new ViewProvider(new ProviderContext($root, ['app.templates_path' => 'resources/views']));
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());

        $container = new ConfiguredApplicationFactory($root)
            ->createContainer(providerNames: ['view-policy', 'fight-common-view']);
        self::assertStringContainsString('Hello, Fight Yii!', $container->get(WebView::class)->render('//home.twig'));
    }
}
