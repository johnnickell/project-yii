<?php

declare(strict_types=1);

use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;
use Yiisoft\Yii\Console\Application;
use Yiisoft\Yii\Console\Command\Serve;
use Yiisoft\Yii\Console\CommandLoader;

require dirname(__DIR__) . '/vendor/autoload.php';

$application = new Application();
$application->setCommandLoader(new CommandLoader(
    new Container(ContainerConfig::create()),
    ['serve' => Serve::class],
));
$application->run();
