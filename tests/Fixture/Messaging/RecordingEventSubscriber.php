<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Application\Messaging\Event\EventSubscriber;
use Fight\Common\Domain\Messaging\Event\EventMessage;

final class RecordingEventSubscriber implements EventSubscriber
{
    /** @var list<string> */
    public array $values = [];

    public static function eventRegistration(): array
    {
        return [JourneyRecorded::class => 'record'];
    }

    public function record(EventMessage $eventMessage): void
    {
        $payload = $eventMessage->payload();
        assert($payload instanceof JourneyRecorded);
        $this->values[] = $payload->value;
    }
}
