<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Messaging\Command\Sync\Routing\CommandRouter;
use Fight\Common\Adapter\Messaging\Command\Sync\Routing\InMemoryCommandRouter;
use Fight\Common\Adapter\Messaging\Command\Sync\RoutingCommandBus;
use Fight\Common\Adapter\Messaging\Event\Sync\SimpleEventDispatcher;
use Fight\Common\Adapter\Messaging\Symfony\MessengerCommandBus;
use Fight\Common\Adapter\Messaging\Symfony\MessengerEventDispatcher;
use Fight\Common\Adapter\ServiceContainer\Yii\YiiCapabilityConfiguration;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Yiisoft\Di\ServiceProviderInterface;

final class MessagingProvider implements ServiceProviderInterface
{
    /** @param array<string, string> $parameters */
    public function __construct(string $root, array $parameters)
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
            InMemoryTransport::class => InMemoryTransport::class,
            SenderInterface::class => static fn (InMemoryTransport $transport): SenderInterface => $transport,
            AsynchronousCommandBus::class => MessengerCommandBus::class,
            AsynchronousEventDispatcher::class => MessengerEventDispatcher::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
