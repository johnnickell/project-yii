<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Sms\Null\NullSmsTransport;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use Yiisoft\Di\ServiceProviderInterface;

final class SmsProvider implements ServiceProviderInterface
{
    /** @param array<string, mixed> $parameters */
    public function __construct(string $root, array $parameters)
    {
    }

    public function getDefinitions(): array
    {
        return [SmsTransport::class => NullSmsTransport::class];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
