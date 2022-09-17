<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Application;

/**
 * Application entry point
 */
$app = new Application(dirname(__DIR__));
$app->run();
