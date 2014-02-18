<?php

/**
 * Setup autoloader
 */

require __DIR__ . '/Craft/Reflect/ClassLoader.php';

$loader = new Craft\Reflect\ClassLoader();
$loader->register();
$loader->vendors([
    'Craft' => __DIR__ . '/Craft',
    'Psr'   => __DIR__ . '/Psr',
    'My'    => dirname($_SERVER['SCRIPT_FILENAME'])
]);


/**
 * Setup tracker
 */

$tracker = new Craft\Trace\Tracker();
$tracker->monitor('craft.app');


/**
 * Load helpers
 */

require __DIR__ . '/helpers.php';