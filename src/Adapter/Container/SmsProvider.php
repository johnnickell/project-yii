<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Sms\Null\NullSmsTransport;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use Yiisoft\Di\ServiceProviderInterface;

/**
 * Class SmsProvider
 */
final readonly class SmsProvider implements ServiceProviderInterface
{
    /**
     * Returns the no-delivery SMS definition
     *
     * @return array<string, mixed>
     */
    public function getDefinitions(): array
    {
        return [SmsTransport::class => NullSmsTransport::class];
    }

    /**
     * Returns no SMS extensions
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return [];
    }
}
