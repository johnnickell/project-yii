<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Yiisoft\Di\ServiceProviderInterface;

/**
 * Class MailProvider
 */
final readonly class MailProvider implements ServiceProviderInterface
{
    /**
     * Returns mail transport definitions
     *
     * @return array<string, mixed>
     */
    public function getDefinitions(): array
    {
        return [
            MailerInterface::class => new Mailer(Transport::fromDsn('null://null'))
        ];
    }

    /**
     * Returns no mail extensions
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return [];
    }
}
