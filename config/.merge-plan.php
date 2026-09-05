<?php

declare(strict_types=1);

// Do not edit. Content will be replaced.
return [
    '/' => [
        'events-web' => [
            'yiisoft/log' => [
                'config/events-web.php',
            ],
            'yiisoft/middleware-dispatcher' => [
                'config/events-web.php',
            ],
        ],
        'events-console' => [
            'yiisoft/log' => [
                'config/events-console.php',
            ],
            'yiisoft/yii-console' => [
                'config/events-console.php',
            ],
        ],
        'di' => [
            'yiisoft/mailer' => [
                'config/di.php',
            ],
            'yiisoft/router-fastroute' => [
                'config/di.php',
            ],
            'yiisoft/validator' => [
                'config/di.php',
            ],
            'yiisoft/cache' => [
                'config/di.php',
            ],
            'yiisoft/router' => [
                'config/di.php',
            ],
            'yiisoft/translator' => [
                'config/di.php',
            ],
            'yiisoft/view' => [
                'config/di.php',
            ],
        ],
        'params' => [
            'yiisoft/mailer' => [
                'config/params.php',
            ],
            'yiisoft/router-fastroute' => [
                'config/params.php',
            ],
            'yiisoft/session' => [
                'config/params.php',
            ],
            'yiisoft/validator' => [
                'config/params.php',
            ],
            'yiisoft/db' => [
                'config/params.php',
            ],
            'yiisoft/router' => [
                'config/params.php',
            ],
            'yiisoft/translator' => [
                'config/params.php',
            ],
            'yiisoft/view' => [
                'config/params.php',
            ],
        ],
        'di-web' => [
            'yiisoft/router-fastroute' => [
                'config/di-web.php',
            ],
            'yiisoft/session' => [
                'config/di-web.php',
            ],
            'yiisoft/view' => [
                'config/di-web.php',
            ],
        ],
        'di-console' => [
            'yiisoft/yii-console' => [
                'config/di-console.php',
            ],
        ],
        'params-console' => [
            'yiisoft/yii-console' => [
                'config/params-console.php',
            ],
        ],
        'project-runtime' => [
            '/' => [
                'project-runtime.php',
            ],
        ],
        'providers' => [
            '/' => [
                'providers.php',
            ],
        ],
        'seams' => [
            '/' => [
                'seams.php',
            ],
        ],
    ],
];
