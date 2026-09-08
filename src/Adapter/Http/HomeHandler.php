<?php

declare(strict_types=1);

namespace App\Adapter\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\View\WebView;

final readonly class HomeHandler implements RequestHandlerInterface
{
    public function __construct(
        private WebView $view,
        private string $routeName,
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface $streamFactory,
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->createResponse(200)
            ->withHeader('Content-Type', 'text/html; charset=utf-8')
            ->withHeader('X-Route-Name', $this->routeName)
            ->withBody($this->streamFactory->createStream($this->view->render('//home.twig')));
    }
}
