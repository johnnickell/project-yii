<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use Fight\Common\Adapter\Cache\Psr6\Psr6Cache;
use Fight\Common\Adapter\Observability\Health\HealthReporter;
use Fight\Common\Application\Cache\Cache;
use Fight\Common\Application\Observability\AuditLog;
use Fight\Common\Application\Observability\HealthAggregator;
use Fight\Common\Application\Observability\HealthCheck;
use Fight\Common\Application\Observability\MetricsCollector;
use Fight\Common\Domain\Observability\AuditEntry;
use Fight\Common\Domain\Observability\HealthResult;
use Fight\Common\Domain\Observability\HealthStatus;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Stringable;
use Yiisoft\Cache\ArrayCache;
use Yiisoft\Log\Logger;
use Yiisoft\Log\PsrTarget;

final class CacheAndObservabilityJourneyTest extends TestCase
{
    public function test_booted_cache_group_owns_native_psr16_and_fight_psr6_behavior(): void
    {
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(
            providerNames: ['cache-policy'],
        );

        $native = $container->get(ArrayCache::class);
        self::assertSame($native, $container->get(CacheInterface::class));
        self::assertTrue($native->set('native-key', 'native-value'));
        self::assertSame('native-value', $container->get(CacheInterface::class)->get('native-key'));
        self::assertInstanceOf(CacheItemPoolInterface::class, $container->get(CacheItemPoolInterface::class));
        self::assertInstanceOf(Psr6Cache::class, $container->get(Psr6Cache::class));

        $loads = 0;
        $loader = static function () use (&$loads): string {
            ++$loads;
            return 'fight-value';
        };
        self::assertSame('fight-value', $container->get(Cache::class)->read('fight-key', $loader, 60));
        self::assertSame('fight-value', $container->get(Cache::class)->read('fight-key', $loader, 60));
        self::assertSame(1, $loads);
    }

    public function test_booted_observability_group_exposes_native_logging_and_shared_health(): void
    {
        $messages = [];
        $sink = new class ($messages) extends AbstractLogger {
            /** @param list<array{level: mixed, message: string}> $messages */
            public function __construct(private array &$messages) {}

            public function log(mixed $level, string|Stringable $message, array $context = []): void
            {
                $this->messages[] = ['level' => $level, 'message' => (string) $message];
            }
        };
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(
            ['app.log_targets' => [new PsrTarget($sink)]],
            ['observability-policy'],
        );

        $logger = $container->get(Logger::class);
        self::assertSame($logger, $container->get(LoggerInterface::class));
        $logger->info('booted-yii-log');
        $logger->flush(true);
        self::assertSame('booted-yii-log', $messages[0]['message']);

        $health = $container->get(HealthAggregator::class);
        self::assertInstanceOf(HealthReporter::class, $health);
        $health->addCheck(new class implements HealthCheck {
            public function check(): HealthResult
            {
                return new HealthResult($this->name(), HealthStatus::healthy(), 'ready');
            }

            public function name(): string
            {
                return 'yii-composition';
            }
        });
        self::assertTrue($health->report()->isHealthy());
        self::assertSame('yii-composition', $health->report()->results()[0]->name());
        $container->get(AuditLog::class)->record(AuditEntry::record('test', 'observed'));
        $container->get(MetricsCollector::class)->increment('yii.observed');
        self::addToAssertionCount(2);
    }
}
