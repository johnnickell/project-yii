<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\MailProvider;
use Fight\Common\Adapter\Mail\Symfony\SymfonyMailTransport;
use Fight\Common\Application\Mail\Transport\MailTransport;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * Class MailProviderTest
 */
#[CoversClass(MailProvider::class)]
final class MailProviderTest extends TestCase
{
    /**
     * Tests the no-delivery mail fallback
     */
    public function testItSuppliesTheNullDsnMailFallback(): void
    {
        $provider = new MailProvider();
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 4))->createContainer(
            providerNames: ['mail-policy', 'fight-common-mail']
        );

        self::assertInstanceOf(SymfonyMailTransport::class, $container->get(MailTransport::class));
        self::assertSame([], $provider->getExtensions());
    }
}
