<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Tests\Fixture\Messaging\JourneyRecorded;
use App\Tests\Fixture\Messaging\RecordingCommandHandler;
use App\Tests\Fixture\Messaging\RecordingEventSubscriber;
use App\Tests\Fixture\Messaging\RecordJourneyCommand;
use Fight\Common\Adapter\Messaging\Command\Sync\Routing\InMemoryCommandRouter;
use Fight\Common\Adapter\Messaging\Event\Sync\SimpleEventDispatcher;
use Fight\Common\Application\Messaging\Command\AsynchronousCommandBus;
use Fight\Common\Application\Messaging\Command\SynchronousCommandBus;
use Fight\Common\Application\Messaging\Event\AsynchronousEventDispatcher;
use Fight\Common\Application\Messaging\Event\SynchronousEventDispatcher;
use Fight\Common\Domain\Messaging\Command\CommandMessage;
use Fight\Common\Domain\Messaging\Event\EventMessage;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Transport\InMemory\InMemoryTransport;

/**
 * Class MessagingJourneyTest
 */
#[CoversNothing]
final class MessagingJourneyTest extends TestCase
{
    /**
     * Tests command and event execution through configured synchronous messaging
     */
    public function testSynchronousHandlersExecuteThroughTheBootedContainer(): void
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

    /**
     * Tests complete command and event envelope transport
     */
    public function testMessengerFallbackTransportsCompleteEnvelopes(): void
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

    /**
     * Tests independent asynchronous fallback selection
     */
    public function testMessengerFallbackIsSelectableIndependently(): void
    {
        $container = new ConfiguredApplicationFactory(dirname(__DIR__, 2))->createContainer(
            providerNames: ['messenger-fallback-policy']
        );

        self::assertInstanceOf(AsynchronousCommandBus::class, $container->get(AsynchronousCommandBus::class));
        self::assertInstanceOf(AsynchronousEventDispatcher::class, $container->get(AsynchronousEventDispatcher::class));
        self::assertFalse($container->has(SynchronousCommandBus::class));
        self::assertFalse($container->has(SynchronousEventDispatcher::class));
    }

    /**
     * Tests the declared asynchronous fallback boundary
     */
    public function testDeclaresMessengerFallbackWithoutClaimingStableYiiQueue(): void
    {
        /** @var array<string, array{owner: string, status: string}> $seams */
        $seams = require dirname(__DIR__, 2).'/config/seams.php';

        self::assertSame(
            ['owner' => 'starter', 'status' => 'configured'],
            $seams['messenger-fallback']
        );
        self::assertSame(
            ['owner' => 'starter', 'status' => 'unavailable'],
            $seams['stable-yii-queue']
        );
    }
}
