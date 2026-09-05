<?php

declare(strict_types=1);

use App\Adapter\Container\ApplicationProvider;
use App\Adapter\Container\CommunicationProvider;
use App\Adapter\Container\FilesProvider;
use App\Adapter\Container\HttpClientProvider;
use App\Adapter\Container\MessagingProvider;
use App\Adapter\Container\ObservabilityProvider;
use App\Adapter\Container\OperationsProvider;
use App\Adapter\Container\PersistenceProvider;
use App\Adapter\Container\SecurityAndValidationProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\FilesystemServiceProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\HttpClientServiceProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\MailServiceProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\MessagingServiceProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\PersistenceServiceProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\RoutingServiceProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\ViewServiceProvider;

return [
    'application' => ApplicationProvider::class,
    'security-and-validation' => SecurityAndValidationProvider::class,
    'messaging-policy' => MessagingProvider::class,
    'persistence-policy' => PersistenceProvider::class,
    'files-policy' => FilesProvider::class,
    'http-client-policy' => HttpClientProvider::class,
    'operations-policy' => OperationsProvider::class,
    'communication-policy' => CommunicationProvider::class,
    'observability-policy' => ObservabilityProvider::class,
    'fight-common-filesystem' => FilesystemServiceProvider::class,
    'fight-common-http-client' => HttpClientServiceProvider::class,
    'fight-common-mail' => MailServiceProvider::class,
    'fight-common-messaging' => MessagingServiceProvider::class,
    'fight-common-persistence' => PersistenceServiceProvider::class,
    'fight-common-routing' => RoutingServiceProvider::class,
    'fight-common-view' => ViewServiceProvider::class,
];
