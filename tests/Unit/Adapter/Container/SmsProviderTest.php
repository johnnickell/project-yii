<?php

declare(strict_types=1);

namespace App\Tests\Unit\Adapter\Container;

use App\Adapter\Container\SmsProvider;
use Fight\Common\Adapter\Sms\Null\NullSmsTransport;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;

#[CoversClass(SmsProvider::class)]
final class SmsProviderTest extends TestCase
{
    public function test_it_declares_the_null_sms_transport(): void
    {
        $provider = new SmsProvider();
        $container = new Container(ContainerConfig::create()->withProviders([$provider]));

        self::assertInstanceOf(NullSmsTransport::class, $container->get(SmsTransport::class));
        self::assertSame([], $provider->getExtensions());
    }
}
