<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Process\Symfony\SymfonyProcessRunner;
use Fight\Common\Application\Process\ProcessRunner;
use Fight\Common\Application\Scheduler\Scheduler;
use Fight\Common\Domain\Value\DateTime\Timezone;
use Yiisoft\Di\ServiceProviderInterface;

final readonly class OperationsProvider implements ServiceProviderInterface
{
    public function __construct(private ProviderContext $context)
    {
    }

    /** @return array<string, mixed> */
    public function getDefinitions(): array
    {
        $schedulerPath = $this->context->absolutePath($this->context->parameters['app.scheduler_path']);
        if (!is_dir($schedulerPath) && !mkdir($schedulerPath, 0777, true) && !is_dir($schedulerPath)) {
            throw new \RuntimeException(sprintf('Could not create scheduler runtime path: %s', $schedulerPath));
        }

        return [
            ProcessRunner::class => SymfonyProcessRunner::class,
            Scheduler::class => static fn (ProcessRunner $runner): Scheduler =>
                Scheduler::withProcessRunner(new Timezone('UTC'), $schedulerPath, $runner),
        ];
    }

    /** @return array<string, mixed> */
    public function getExtensions(): array
    {
        return [];
    }
}
