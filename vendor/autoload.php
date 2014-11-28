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

if(!defined('__ENV__')) {
    define('__ENV__', 'prod');
}


/**
 * Setup AutoLoader
 */

require __DIR__ . '/Craft/Kit/ClassLoader.php';

$loader = new Craft\Kit\ClassLoader([
    'Craft'  => __DIR__ . '/Craft',
    'Whoops' => __DIR__ . '/Whoops',
    'Psr'    => __DIR__ . '/Psr',
    'App'    => __APP__
]);

spl_autoload_register($loader);


/**
 * Load helpers
 */

require __DIR__ . '/Craft/array.php';
require __DIR__ . '/Craft/helpers.php';