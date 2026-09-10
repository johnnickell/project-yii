<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Tests\Fixture\Messaging\JourneyRecorded;
use App\Tests\Fixture\Messaging\RecordJourneyCommand;
use App\Tests\Fixture\Messaging\RecordingCommandHandler;
use App\Tests\Fixture\Messaging\RecordingEventSubscriber;
use Fight\Common\Adapter\Messaging\Command\Sync\Routing\InMemoryCommandRouter;
use Fight\Common\Adapter\Messaging\Event\Sync\SimpleEventDispatcher;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Fight\Common\Application\Messaging\Event\SynchronousEventDispatcher;
use Fight\Common\Domain\Messaging\Command\CommandMessage;
use Fight\Common\Domain\Messaging\Event\EventMessage;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

#[CoversNothing]
final class MessagingJourneyTest extends TestCase
{
    public function test_synchronous_command_and_event_handlers_execute_through_the_booted_container(): void
    {
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 2))->createContainer();
        $commandHandler = new RecordingCommandHandler();
        $container->get(InMemoryCommandRouter::class)->registerHandler(RecordJourneyCommand::class, $commandHandler);
        $subscriber = new RecordingEventSubscriber();
        $container->get(SimpleEventDispatcher::class)->register($subscriber);

        $container->get(SynchronousCommandBus::class)->execute(new RecordJourneyCommand('sync-command'));
        $container->get(SynchronousEventDispatcher::class)->trigger(new JourneyRecorded('sync-event'));

        self::assertSame(['sync-command'], $commandHandler->values);
        self::assertSame(['sync-event'], $subscriber->values);
    }

    public function test_messenger_fallback_transports_complete_command_and_event_envelopes(): void
    {
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 2))->createContainer();
        $container->get(AsynchronousCommandBus::class)->execute(new RecordJourneyCommand('transport-command'));
        $container->get(AsynchronousEventDispatcher::class)->trigger(new JourneyRecorded('transport-event'));
        $sent = $container->get(InMemoryTransport::class)->getSent();

        self::assertCount(2, $sent);
        self::assertInstanceOf(CommandMessage::class, $sent[0]->getMessage());
        self::assertSame('command', $sent[0]->getMessage()->type()->value);
        self::assertSame(['value' => 'transport-command'], $sent[0]->getMessage()->payload()->toArray());
        self::assertMatchesRegularExpression('/^[a-f0-9-]{36}$/', $sent[0]->getMessage()->id()->toString());
        self::assertInstanceOf(EventMessage::class, $sent[1]->getMessage());
        self::assertSame('event', $sent[1]->getMessage()->type()->value);
        self::assertSame(['value' => 'transport-event'], $sent[1]->getMessage()->payload()->toArray());
        self::assertMatchesRegularExpression('/^[a-f0-9-]{36}$/', $sent[1]->getMessage()->id()->toString());
        self::assertNotSame($sent[0]->getMessage()->id()->toString(), $sent[1]->getMessage()->id()->toString());
    }

    public function test_messenger_fallback_is_selectable_independently_from_synchronous_messaging(): void
    {
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 2))->createContainer(
            providerNames: ['messenger-fallback-policy'],
        );

        self::assertInstanceOf(AsynchronousCommandBus::class, $container->get(AsynchronousCommandBus::class));
        self::assertInstanceOf(AsynchronousEventDispatcher::class, $container->get(AsynchronousEventDispatcher::class));
        self::assertFalse($container->has(SynchronousCommandBus::class));
        self::assertFalse($container->has(SynchronousEventDispatcher::class));
    }

    public function test_declares_messenger_fallback_without_claiming_stable_yii_queue(): void
    {
        /** @var array<string, array{owner: string, status: string}> $seams */
        $seams = require dirname(__DIR__, 2) . '/config/seams.php';

        self::assertSame(
            ['owner' => 'starter', 'status' => 'configured'],
            $seams['messenger-fallback'],
        );
        self::assertSame(
            ['owner' => 'starter', 'status' => 'unavailable'],
            $seams['stable-yii-queue'],
        );
    }
}
