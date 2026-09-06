<?php

declare(strict_types=1);

use App\Adapter\Container\FilesProvider;
use App\Adapter\Container\HttpApplicationProvider;
use App\Adapter\Container\HttpClientProvider;
use App\Adapter\Container\MailProvider;
use App\Adapter\Container\MessengerFallbackProvider;
use App\Adapter\Container\ObservabilityProvider;
use App\Adapter\Container\OperationsProvider;
use App\Adapter\Container\PersistenceProvider;
use App\Adapter\Container\PublicationProvider;
use App\Adapter\Container\RoutingProvider;
use App\Adapter\Container\SecurityAndValidationProvider;
use App\Adapter\Container\SmsProvider;
use App\Adapter\Container\SynchronousMessagingProvider;
use App\Adapter\Container\ViewProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\FilesystemServiceProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\HttpClientServiceProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\MailServiceProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\MessagingServiceProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\PersistenceServiceProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\RoutingServiceProvider;
use Fight\Common\Adapter\ServiceContainer\Yii\ViewServiceProvider;

return [
    'routing-policy' => RoutingProvider::class,
    'view-policy' => ViewProvider::class,
    'http-application' => HttpApplicationProvider::class,
    'security-and-validation' => SecurityAndValidationProvider::class,
    'synchronous-messaging-policy' => SynchronousMessagingProvider::class,
    'messenger-fallback-policy' => MessengerFallbackProvider::class,
    'persistence-policy' => PersistenceProvider::class,
    'files-policy' => FilesProvider::class,
    'http-client-policy' => HttpClientProvider::class,
    'operations-policy' => OperationsProvider::class,
    'mail-policy' => MailProvider::class,
    'sms-policy' => SmsProvider::class,
    'publication-policy' => PublicationProvider::class,
    'observability-policy' => ObservabilityProvider::class,
    'fight-common-filesystem' => FilesystemServiceProvider::class,
    'fight-common-http-client' => HttpClientServiceProvider::class,
    'fight-common-mail' => MailServiceProvider::class,
    'fight-common-messaging' => MessagingServiceProvider::class,
    'fight-common-persistence' => PersistenceServiceProvider::class,
    'fight-common-routing' => RoutingServiceProvider::class,
    'fight-common-view' => ViewServiceProvider::class,
];
