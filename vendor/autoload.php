<?php

/**
 * Define constants
 */

define('__ROOT__',   dirname(__DIR__));
define('__VENDOR__', __ROOT__ . '/vendor');
define('__PUBLIC__', __ROOT__ . '/public');
define('__APP__',    __ROOT__ . '/app');
define('__NOW__',    time());


/**
 * Setup AutoLoader
 */

require __DIR__ . '/Craft/Reflect/ClassLoader.php';

$loader = new Craft\Reflect\ClassLoader;
$loader->register();

$loader->add('Craft', __VENDOR__ . '/Craft');
$loader->add('Forge', __VENDOR__ . '/Forge');
$loader->add('Psr',   __VENDOR__ . '/Psr');
$loader->add('My',    __APP__);


/**
 * Start logger
 */

Forge\Logger::info('Hello :)');


/**
 * Load helpers
 */

require __DIR__ . '/Craft/helpers.php';