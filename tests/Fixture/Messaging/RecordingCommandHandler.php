<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Messaging;

use Fight\Common\Application\Messaging\Command\CommandHandler;
use Fight\Common\Domain\Messaging\Command\CommandMessage;

/**
 * Class RecordingCommandHandler
 */
final class RecordingCommandHandler implements CommandHandler
{
    /** @var list<string> */
    public array $values = [];

    /**
     * @inheritDoc
     */
    public static function commandRegistration(): string
    {
        return RecordJourneyCommand::class;
    }

    /**
     * @inheritDoc
     */
    public function handle(CommandMessage $commandMessage): void
    {
        $payload = $commandMessage->payload();
        assert($payload instanceof RecordJourneyCommand);
        $this->values[] = $payload->value;
    }
}
