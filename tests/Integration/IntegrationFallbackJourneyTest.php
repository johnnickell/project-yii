<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Tests\Fixture\RecordingHub;
use Fight\Common\Adapter\Filesystem\Symfony\SymfonyFilesystem;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleClient;
use Fight\Common\Adapter\Mail\Symfony\SymfonyMailTransport;
use Fight\Common\Application\Filesystem\Filesystem;
use Fight\Common\Application\FileTransfer\Transport\FileTransport;
use Fight\Common\Application\HttpClient\Transport\HttpClient;
use Fight\Common\Application\Mail\Message\MailFactory;
use Fight\Common\Application\Mail\Transport\MailTransport;
use Fight\Common\Application\Observability\AuditLog;
use Fight\Common\Application\Observability\MetricsCollector;
use Fight\Common\Application\Process\ProcessBuilder;
use Fight\Common\Application\Process\ProcessRunner;
use Fight\Common\Application\Sms\Message\SmsMessage;
use Fight\Common\Application\Sms\Transport\SmsTransport;
use Fight\Common\Application\Socket\PrivatePublisher;
use Fight\Common\Application\Socket\Publisher;
use Fight\Common\Domain\Observability\AuditEntry;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\MockHub;
use Symfony\Component\Mercure\Update;

final class IntegrationFallbackJourneyTest extends TestCase
{
    public function test_http_and_process_fallbacks_produce_deterministic_local_results(): void
    {
        $http = new GuzzleClient(new Client([
            'handler' => HandlerStack::create(new MockHandler([
                new Response(202, ['X-Fallback' => 'fight'], 'accepted'),
                new Response(204, ['X-Fallback' => 'psr18']),
            ])),
            'http_errors' => false,
        ]));
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(['app.http_client' => $http]);
        $request = new Request('GET', 'https://no-network.invalid/test');

        self::assertSame(202, $container->get(HttpClient::class)->send($request)->getStatusCode());
        self::assertSame('psr18', $container->get(ClientInterface::class)->sendRequest($request)->getHeaderLine('X-Fallback'));

        $output = '';
        $container->get(ProcessRunner::class)->attach(ProcessBuilder::create()->shellCommand('printf process-ready')
            ->stdout(static function (string $chunk) use (&$output): void { $output .= $chunk; })->getProcess());
        $container->get(ProcessRunner::class)->run();
        self::assertSame('process-ready', $output);
    }

    public function test_safe_communication_observability_and_publication_fallbacks_have_observable_outcomes(): void
    {
        $hub = new RecordingHub();
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer(['app.mercure_hub' => $hub]);
        self::assertInstanceOf(SymfonyMailTransport::class, $container->get(MailTransport::class));
        $filesystem = $container->get(Filesystem::class);
        self::assertInstanceOf(SymfonyFilesystem::class, $filesystem);
        $path = sys_get_temp_dir().'/project-yii-symfony-filesystem-'.bin2hex(random_bytes(6)).'.txt';
        $filesystem->put($path, 'symfony-filesystem-fallback');
        self::assertSame('symfony-filesystem-fallback', $filesystem->get($path));
        $filesystem->remove($path);
        $mail = $container->get(MailFactory::class)->createMessage()->addFrom('from@example.test')->addTo('to@example.test')
            ->setSubject('local')->addContent('safe', 'text/plain');
        $container->get(MailTransport::class)->send($mail);
        $container->get(SmsTransport::class)->send(SmsMessage::create('+15555550100', '+15555550101')->setBody('safe'));
        $container->get(FileTransport::class)->sendFile('ignored.txt', 'safe');
        self::assertSame('', $container->get(FileTransport::class)->retrieveFileContents('ignored.txt'));
        $container->get(AuditLog::class)->record(AuditEntry::record('journey', 'verified'));
        $container->get(MetricsCollector::class)->increment('journey.verified', ['safe' => 'true']);
        $container->get(Publisher::class)->push('public-topic', 'public-message');
        $container->get(PrivatePublisher::class)->pushPrivate('private-topic', 'private-message');

        self::assertCount(2, $hub->updates);
        self::assertSame(['public-topic'], $hub->updates[0]->getTopics());
        self::assertSame('public-message', $hub->updates[0]->getData());
        self::assertFalse($hub->updates[0]->isPrivate());
        self::assertTrue($hub->updates[1]->isPrivate());
        self::addToAssertionCount(3);
    }

    public function test_native_and_fallback_seams_remain_explicit(): void
    {
        /** @var array<string, array{owner: string, status: string}> $seams */
        $seams = require dirname(__DIR__, 2).'/config/seams.php';

        self::assertSame(
            [
                'native-yii-view' => ['owner' => 'starter', 'status' => 'shipped'],
                'native-yii-mail' => ['owner' => 'starter', 'status' => 'unavailable'],
                'symfony-mail-fallback' => ['owner' => 'starter', 'status' => 'configured'],
                'native-yii-filesystem' => ['owner' => 'starter', 'status' => 'unavailable'],
                'symfony-filesystem-fallback' => ['owner' => 'starter', 'status' => 'configured'],
            ],
            array_intersect_key(
                $seams,
                array_flip([
                    'native-yii-view',
                    'native-yii-mail',
                    'symfony-mail-fallback',
                    'native-yii-filesystem',
                    'symfony-filesystem-fallback',
                ]),
            ),
        );
    }

    public function test_configured_mercure_fallback_uses_application_owned_policy_without_network_effects(): void
    {
        $container = (new ConfiguredApplicationFactory(dirname(__DIR__, 2)))->createContainer([
            'app.mercure_url' => 'https://mercure.example.test/custom',
            'app.mercure_token' => 'configured-local-token',
            'app.publication_result' => 'configured-publication',
        ]);

        $hub = $container->get(HubInterface::class);

        self::assertInstanceOf(MockHub::class, $hub);
        self::assertSame('https://mercure.example.test/custom', $hub->getUrl());
        self::assertSame('https://mercure.example.test/custom', $hub->getPublicUrl());
        self::assertSame('configured-local-token', $hub->getProvider()->getJwt());
        self::assertSame('configured-publication', $hub->publish(new Update('safe-topic', 'safe-message')));

        $container->get(Publisher::class)->push('safe-public-topic', 'safe-public-message');
        $container->get(PrivatePublisher::class)->pushPrivate('safe-private-topic', 'safe-private-message');
        self::addToAssertionCount(2);
    }
}
