<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Adapter\Container\MailProvider;
use Fight\Common\Adapter\Mail\Symfony\SymfonyMailTransport;
use Fight\Common\Application\Mail\Transport\MailTransport;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MailProvider::class)]
final class MailProviderTest extends TestCase
{
    public function test_it_supplies_the_null_dsn_mail_fallback(): void
    {
        $provider = new MailProvider();
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 4))->createContainer(
            providerNames: ['mail-policy', 'fight-common-mail'],
        );

        self::assertInstanceOf(SymfonyMailTransport::class, $container->get(MailTransport::class));
        self::assertSame([], $provider->getExtensions());
    }
}
