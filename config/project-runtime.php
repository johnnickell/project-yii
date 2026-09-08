<?php

declare(strict_types=1);

return [
    'app.hmac_identity' => getenv('FIGHT_HMAC_PUBLIC'),
    'app.hmac_private_hex' => getenv('FIGHT_HMAC_PRIVATE'),
    'app.jwt_secret_hex' => getenv('FIGHT_JWT_SECRET'),
    'app.storage_path' => 'var/storage/flysystem',
    'app.scheduler_path' => 'var/runtime/scheduler',
    'app.route_path' => '/',
    'app.route_name' => 'home',
    'app.route_methods' => ['GET'],
    'app.route_action' => [App\Adapter\Http\HomeHandler::class, 'handle'],
    'app.templates_path' => 'resources/views',
    'app.log_targets' => [],
    'app.mercure_url' => 'http://localhost/.well-known/mercure',
    'app.mercure_token' => 'project-yii-local',
    'app.publication_result' => 'published',
];
