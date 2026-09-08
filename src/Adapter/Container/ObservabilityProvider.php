<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Observability\Audit\NullAuditLog;
use Fight\Common\Adapter\Observability\Metrics\NullMetricsCollector;
use Fight\Common\Application\Observability\AuditLog;
use Fight\Common\Application\Observability\MetricsCollector;
use Yiisoft\Di\ServiceProviderInterface;

final readonly class ObservabilityProvider implements ServiceProviderInterface
{
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
