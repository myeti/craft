<?php

/**
 * Setup autoloader
 */

require __DIR__ . '/Craft/Reflect/ClassLoader.php';
require __DIR__ . '/Forge/Autoloader.php';

Forge\Autoloader::register();
Forge\Autoloader::vendors([
    'Craft' => __DIR__ . '/Craft',
    'Forge' => __DIR__ . '/Forge',
    'Psr'   => __DIR__ . '/Psr',
    'My'    => dirname($_SERVER['SCRIPT_FILENAME'])
]);

Forge\Logger::info('Hello :)');


/**
 * Load helpers
 */

require __DIR__ . '/helpers.php';