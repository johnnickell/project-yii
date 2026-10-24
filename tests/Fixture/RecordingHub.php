<?php

declare(strict_types=1);

namespace App\Tests\Fixture;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Jwt\TokenFactoryInterface;
use Symfony\Component\Mercure\Update;

/**
 * Class RecordingHub
 */
final class RecordingHub implements HubInterface
{
    /** @var list<Update> */
    public array $updates = [];

    /**
     * @inheritDoc
     */
    public function getPublicUrl(): string
    {
        return 'https://mercure.example.test/.well-known/mercure';
    }

    /**
     * @inheritDoc
     */
    public function getFactory(): ?TokenFactoryInterface
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function publish(Update $update): string
    {
        $this->updates[] = $update;

        return 'recorded-'.count($this->updates);
    }
}
