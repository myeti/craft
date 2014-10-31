<?php

/**
 * Hello !
 * I'll help you build your console app :)
 */
require 'vendor/autoload.php';

use Craft\App;
use Craft\Router;

/**
 * You can now create your router based on your cli commands.
 */
$router = new Router\Basic([
    'hello'  => 'App\Cli\Hello',
    'deploy' => 'App\Cli\Deploy',
]);


/**
 * Create your application using these components
 */
$app = new App\Console($router);


/**
 * You can now run your app,
 * well done !
 */
$app->run();