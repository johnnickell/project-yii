<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Process\Symfony\SymfonyProcessRunner;
use Fight\Common\Application\Process\ProcessRunner;
use Fight\Common\Application\Scheduler\Scheduler;
use Fight\Common\Domain\Value\DateTime\Timezone;
use Yiisoft\Di\ServiceProviderInterface;

/**
 * Class OperationsProvider
 */
final readonly class OperationsProvider implements ServiceProviderInterface
{
    /**
     * Constructs OperationsProvider
     */
    public function __construct(private ProviderContext $context)
    {
    }

    /**
     * Returns process and scheduler definitions with the configured runtime path
     *
     * @return array<string, mixed>
     */
    public function getDefinitions(): array
    {
        $schedulerPath = $this->context->absolutePath($this->context->parameters['app.scheduler_path']);
        if (!is_dir($schedulerPath) && !mkdir($schedulerPath, 0777, true) && !is_dir($schedulerPath)) {
            throw new \RuntimeException(sprintf('Could not create scheduler runtime path: %s', $schedulerPath));
        }

        return [
            ProcessRunner::class => SymfonyProcessRunner::class,
            Scheduler::class     => static fn (ProcessRunner $runner): Scheduler =>
                Scheduler::withProcessRunner(new Timezone('UTC'), $schedulerPath, $runner)
        ];
    }

    /**
     * Returns no operations extensions
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return [];
    }
}
