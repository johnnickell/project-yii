<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Domain\Messaging\Event\Event;

/**
 * Class JourneyRecorded
 */
final readonly class JourneyRecorded implements Event
{
    /**
     * Constructs JourneyRecorded
     */
    public function __construct(public string $value)
    {
    }

    /**
     * @inheritDoc
     */
    public static function fromArray(array $data): static
    {
        return new self((string) $data['value']);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return ['value' => $this->value];
    }
}
