<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Observability\Audit\NullAuditLog;
use Fight\Common\Adapter\Observability\Metrics\NullMetricsCollector;
use Fight\Common\Application\Observability\AuditLog;
use Fight\Common\Application\Observability\MetricsCollector;
use Yiisoft\Di\ServiceProviderInterface;

final class ObservabilityProvider implements ServiceProviderInterface
{
    /** @param array<string, string> $parameters */
    public function __construct(string $root, array $parameters)
    {
    }

    public function getDefinitions(): array
    {
        return [
            AuditLog::class => NullAuditLog::class,
            MetricsCollector::class => NullMetricsCollector::class,
        ];
    }

    public function getExtensions(): array
    {
        return [];
    }
}
