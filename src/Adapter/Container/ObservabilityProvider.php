<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\Observability\Audit\NullAuditLog;
use Fight\Common\Adapter\Observability\Metrics\NullMetricsCollector;
use Fight\Common\Application\Observability\AuditLog;
use Fight\Common\Application\Observability\MetricsCollector;
use Fight\Common\Adapter\Observability\Health\HealthReporter;
use Fight\Common\Application\Observability\HealthAggregator;
use Psr\Log\LoggerInterface;
use Yiisoft\Di\ServiceProviderInterface;
use Yiisoft\Log\Logger;

final readonly class ObservabilityProvider implements ServiceProviderInterface
{
    public function __construct(private ProviderContext $context)
    {
    }

    /** @return array<string, mixed> */
    public function getDefinitions(): array
    {
        return [
            Logger::class => fn (): Logger => new Logger($this->context->parameters['app.log_targets']),
            LoggerInterface::class => static fn (Logger $logger): LoggerInterface => $logger,
            HealthReporter::class => HealthReporter::class,
            HealthAggregator::class => static fn (HealthReporter $reporter): HealthAggregator => $reporter,
            AuditLog::class => NullAuditLog::class,
            MetricsCollector::class => NullMetricsCollector::class,
        ];
    }

    /** @return array<string, mixed> */
    public function getExtensions(): array
    {
        return [];
    }
}
