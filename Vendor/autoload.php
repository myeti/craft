<?php

/**
 * Define constants
 */

define('__APP__', dirname($_SERVER['SCRIPT_FILENAME']));
define('__NOW__', time());


/**
 * Setup AutoLoader
 */

require __DIR__ . '/Craft/Reflect/ClassLoader.php';

$loader = new Craft\Reflect\ClassLoader;
$loader->register();

$loader->add('Craft', __DIR__ . '/Craft');
$loader->add('Forge', __DIR__ . '/Forge');
$loader->add('Psr',   __DIR__ . '/Psr');
$loader->add('My',    __APP__);


/**
 * Start logger
 */

Forge\Logger::info('Hello :)');


/**
 * Load helpers
 */

require __DIR__ . '/Craft/helpers.php';