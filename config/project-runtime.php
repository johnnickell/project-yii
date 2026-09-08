<?php

declare(strict_types=1);

return [
    // Deterministic development-only defaults. Override every credential in deployed environments.
    'app.hmac_identity' => 'project-yii-local',
    'app.hmac_private_hex' => '1111111111111111111111111111111111111111111111111111111111111111',
    'app.jwt_secret_hex' => '2222222222222222222222222222222222222222222222222222222222222222',
    'app.jwt_algorithm' => 'HS256',
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
