<?php

declare(strict_types=1);

namespace App\Adapter\Http;

use App\Web\HomeHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class HomeMiddleware implements MiddlewareInterface
{
    public function __construct(private HomeHandler $handler)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->handler->handle($request);
    }
}
