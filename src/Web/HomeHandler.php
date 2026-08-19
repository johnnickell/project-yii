<?php

declare(strict_types=1);

namespace App\Web;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\View\WebView;

final readonly class HomeHandler implements RequestHandlerInterface
{
    public function __construct(private WebView $view)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(
            200,
            ['Content-Type' => 'text/html; charset=utf-8'],
            $this->view->render('//home.twig')
        );
    }
}
