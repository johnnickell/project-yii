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
    /** @param array<string, string> $parameters */
    public function __construct(private string $root, private array $parameters)
    {
    }

    public function getDefinitions(): array
    {
        $schedulerPath = $this->absolutePath($this->parameters['app.scheduler_path']);
        if (!is_dir($schedulerPath) && !mkdir($schedulerPath, 0777, true) && !is_dir($schedulerPath)) {
            throw new \RuntimeException(sprintf('Could not create scheduler runtime path: %s', $schedulerPath));
        }

        return [
            ProcessRunner::class => SymfonyProcessRunner::class,
            Scheduler::class => static fn (ProcessRunner $runner): Scheduler =>
                Scheduler::withProcessRunner(new Timezone('UTC'), $schedulerPath, $runner),
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }

    private function absolutePath(string $path): string
    {
        return str_starts_with($path, '/') ? $path : $this->root.'/'.$path;
    }
}
