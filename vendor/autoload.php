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


/**
 * Setup AutoLoader
 */

require __DIR__ . '/Craft/Kit/ClassLoader.php';

$loader = new Craft\Kit\ClassLoader;
$loader->autoload();

$loader->add('Craft',  __DIR__ . '/Craft');
$loader->add('Whoops', __DIR__ . '/Whoops');
$loader->add('Psr',    __DIR__ . '/Psr');
$loader->add('App',     __APP__);


/**
 * Load helpers
 */

require __DIR__ . '/Craft/array.php';
require __DIR__ . '/Craft/helpers.php';


/**
 * Setup env mode
 */

if(php_sapi_name() == 'cli') {
    Craft\Box\Mog::cli(__ROOT__);
}
else {
    Craft\Box\Mog::web('dev');
}


/**
 * Setup logger
 */

Craft\Debug\Logger::register(
    new Craft\Debug\Logger\FileLogger(__APP__ . '/logs')
);