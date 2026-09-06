<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Messaging\Command\Sync\Routing\CommandRouter;
use Fight\Common\Adapter\Messaging\Command\Sync\Routing\InMemoryCommandRouter;
use Fight\Common\Adapter\Messaging\Command\Sync\RoutingCommandBus;
use Fight\Common\Adapter\Messaging\Event\Sync\SimpleEventDispatcher;
use Fight\Common\Adapter\ServiceContainer\Yii\YiiCapabilityConfiguration;
use Yiisoft\Di\ServiceProviderInterface;

final readonly class SynchronousMessagingProvider implements ServiceProviderInterface
{
    public function __construct(ProviderContext $context)
    {
    }

    public function getDefinitions(): array
    {
        $router = new InMemoryCommandRouter();
        $events = new SimpleEventDispatcher();

        return [
            ...YiiCapabilityConfiguration::messaging(new RoutingCommandBus($router), $events),
            InMemoryCommandRouter::class => $router,
            CommandRouter::class => $router,
            SimpleEventDispatcher::class => $events,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
