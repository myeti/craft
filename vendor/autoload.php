<?php

/**
 * Define constants
 */

define('__ROOT__',   dirname(__DIR__));
define('__VENDOR__', __ROOT__ . '/vendor');
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

$loader->add('Whoops', __VENDOR__ . '/Whoops');
$loader->add('My',    __APP__);


/**
 * Load helpers
 */

require __DIR__ . '/Craft/helpers.php';