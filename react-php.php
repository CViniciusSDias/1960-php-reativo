<?php

use React\EventLoop\Factory;

require_once 'vendor/autoload.php';

$loop = Factory::create();

$loop->addTimer(1, function () {
    echo "1 segundo" . PHP_EOL;
});

$loop->run();
