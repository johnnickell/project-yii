<?php

declare(strict_types=1);

namespace App\Adapter\Container;

use Fight\Common\Adapter\HttpClient\Guzzle\GuzzleClient;
use Fight\Common\Application\HttpClient\Transport\HttpClient;
use GuzzleHttp\Client;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Yiisoft\Di\ServiceProviderInterface;

/**
 * Class HttpClientProvider
 */
final readonly class HttpClientProvider implements ServiceProviderInterface
{
    /**
     * Constructs HttpClientProvider
     */
    public function __construct(private readonly ProviderContext $context)
    {
    }

    /**
     * Returns HTTP transport definitions
     *
     * @return array<string, mixed>
     */
    public function getDefinitions(): array
    {
        return [
            HttpClient::class               => $this->httpClient(),
            RequestFactoryInterface::class  => static fn (): RequestFactoryInterface => new Psr17Factory(),
            ResponseFactoryInterface::class => static fn (): ResponseFactoryInterface => new Psr17Factory(),
            StreamFactoryInterface::class   => static fn (): StreamFactoryInterface => new Psr17Factory()
        ];
    }

    /**
     * Returns no HTTP transport extensions
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return [];
    }

    /**
     * Returns the configured HTTP transport or safe Guzzle fallback
     */
    private function httpClient(): HttpClient
    {
        $configured = $this->context->parameters['app.http_client'] ?? null;

        return $configured instanceof HttpClient ? $configured : new GuzzleClient(new Client(['http_errors' => false]));
    }
}
