<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use App\Adapter\Bootstrap\ConfiguredApplicationFactory;
use App\Tests\Fixture\RecordingHub;
use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleClient;
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
}
