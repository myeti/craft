<?php

/**
 * Define constants
 */

if(!defined('__ROOT__')) {
    define('__ROOT__', dirname(__DIR__));
}

if(!defined('__APP__')) {
    define('__APP__', __ROOT__ . '/app');
}

if(!defined('__NOW__')) {
    define('__NOW__', time());
}


/**
 * Setup AutoLoader
 */

require __DIR__ . '/Craft/Reflect/ClassLoader.php';

$loader = new Craft\Reflect\ClassLoader;
$loader->register();

$loader->add('Craft',  __DIR__ . '/Craft');
$loader->add('Forge',  __DIR__ . '/Forge');
$loader->add('Psr',    __DIR__ . '/Psr');
$loader->add('Whoops', __DIR__ . '/Whoops');
$loader->add('My',     __APP__);


/**
 * Set base url
 */

if(defined('__BASE__')) {
    Craft\Box\Mog::base(__BASE__);
}
else {
    define('__BASE__', Craft\Box\Mog::base());
}


/**
 * Setup logger
 */
Craft\Trace\Logger::register(
    new Craft\Trace\Logger\FileLogger(__APP__ . '/logs')
);


/**
 * Load helpers
 */

require __DIR__ . '/Craft/array.php';
require __DIR__ . '/Craft/helpers.php';