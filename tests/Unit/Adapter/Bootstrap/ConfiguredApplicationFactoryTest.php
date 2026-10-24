<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Bootstrap;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use InvalidArgumentException;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Yiisoft\Di\Container;

/**
 * Class ConfiguredApplicationFactoryTest
 */
#[CoversClass(ConfiguredApplicationFactory::class)]
final class ConfiguredApplicationFactoryTest extends TestCase
{
    /**
     * Tests configured container and application creation
     */
    public function testItBuildsTheConfiguredContainerAndApplication(): void
    {
        $factory = new ConfiguredApplicationFactory(dirname(__DIR__, 4));

        self::assertInstanceOf(Container::class, $factory->createContainer());
        self::assertSame(200, $factory->createApplication()->handle(new ServerRequest('GET', '/'))->getStatusCode());
    }

    /**
     * Tests explicit provider selection and unknown-name rejection
     */
    public function testItBuildsOnlyNamedProvidersAndRejectsUnknownNames(): void
    {
        $factory = new ConfiguredApplicationFactory(dirname(__DIR__, 4));
        self::assertInstanceOf(Container::class, $factory->createContainer(providerNames: ['cache-policy']));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('unknown-provider');
        $factory->createContainer(providerNames: ['unknown-provider']);
    }
}
