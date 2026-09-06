<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Messaging\Symfony\MessengerCommandBus;
use Fight\Common\Adapter\Messaging\Symfony\MessengerEventDispatcher;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Yiisoft\Di\ServiceProviderInterface;

final readonly class MessengerFallbackProvider implements ServiceProviderInterface
{
    public function __construct(ProviderContext $context)
    {
    }

    public function getDefinitions(): array
    {
        return [
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
