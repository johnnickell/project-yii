<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Messaging\Command\Sync\Routing\CommandRouter;
use Fight\Common\Adapter\Messaging\Command\Sync\Routing\InMemoryCommandRouter;
use Fight\Common\Adapter\Messaging\Command\Sync\RoutingCommandBus;
use Fight\Common\Adapter\Messaging\Event\Sync\SimpleEventDispatcher;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\SynchronousEventDispatcher;
use Yiisoft\Di\ServiceProviderInterface;

/**
 * Class SynchronousMessagingProvider
 */
final readonly class SynchronousMessagingProvider implements ServiceProviderInterface
{
    /**
     * Returns shared synchronous messaging definitions
     *
     * @return array<string, mixed>
     */
    public function getDefinitions(): array
    {
        $router = new InMemoryCommandRouter();
        $events = new SimpleEventDispatcher();

        return [
            SynchronousCommandBus::class      => new RoutingCommandBus($router),
            SynchronousEventDispatcher::class => $events,
            InMemoryCommandRouter::class      => $router,
            CommandRouter::class              => $router,
            SimpleEventDispatcher::class      => $events
        ];
    }

    /**
     * Returns no synchronous messaging extensions
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return [];
    }
}
