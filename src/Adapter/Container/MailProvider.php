<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\ServiceContainer\Yii\YiiCapabilityConfiguration;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Yiisoft\Di\ServiceProviderInterface;

final readonly class MailProvider implements ServiceProviderInterface
{
    public function getDefinitions(): array
    {
        return [
            ...YiiCapabilityConfiguration::mail(new Mailer(Transport::fromDsn('null://null'))),
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
