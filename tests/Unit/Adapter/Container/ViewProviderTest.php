<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\ProviderContext;
use App\Adapter\Container\ViewProvider;
use Fight\Common\Application\Templating\TemplateEngine;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Yiisoft\View\WebView;

/**
 * Class ViewProviderTest
 */
#[CoversClass(ViewProvider::class)]
final class ViewProviderTest extends TestCase
{
    /**
     * Tests Twig rendering through the Yii web view
     */
    public function testItRendersTwigThroughTheYiiWebView(): void
    {
        $root = dirname(__DIR__, 4);
        $provider = new ViewProvider(new ProviderContext($root, ['app.templates_path' => 'resources/views']));
        self::assertNotEmpty($provider->getDefinitions());
        self::assertSame([], $provider->getExtensions());

        $container = new ConfiguredApplicationFactory($root)
            ->createContainer(providerNames: ['view-policy', 'fight-common-view']);
        self::assertStringContainsString('Hello, Fight Yii!', $container->get(WebView::class)->render('//home.twig'));
    }

    /**
     * Tests native rendering uses the configured template root and caller data
     */
    public function testNativeRenderingUsesTheConfiguredTemplateRoot(): void
    {
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 4))->createContainer(
            ['app.templates_path' => 'tests/Fixture/Http/configured-templates'],
            ['view-policy', 'fight-common-view']
        );

        self::assertSame(
            'Configured native greeting: Ada &amp; Grace',
            $container->get(TemplateEngine::class)->render('greeting.php', ['name' => 'Ada & Grace'])
        );
    }
}
