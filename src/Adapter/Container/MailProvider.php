<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\ServiceContainer\Yii\YiiCapabilityConfiguration;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Yiisoft\Di\ServiceProviderInterface;

final class MailProvider implements ServiceProviderInterface
{
    /** @param array<string, mixed> $parameters */
    public function __construct(string $root, array $parameters)
    {
    }

    public function getDefinitions(): array
    {
        return YiiCapabilityConfiguration::mail(new Mailer(Transport::fromDsn('null://null')));
    }

    public function getExtensions(): array
    {
        return [];
    }
}
