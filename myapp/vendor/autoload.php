<?php

/**
 * Setup autoloader
 */

require __DIR__ . '/Craft/Service/Loader.php';

$loader = new Craft\Service\Loader();
$loader->register();
$loader->vendors([
    'Craft' => __DIR__ . '/Craft',
    'Psr'   => __DIR__ . '/Psr',
    'My'    => dirname($_SERVER['SCRIPT_FILENAME'])
]);


/**
 * Setup dev tools
 */

$logger = new Craft\Service\Logger();
$tracker = new Craft\Service\Tracker();
$tracker->start('craft.app');


/**
 * Setup session
 */

ini_set('session.use_trans_sid', 0);
ini_set('session.use_only_cookies', 1);
ini_set("session.cookie_lifetime", 604800);
ini_set("session.gc_maxlifetime", 604800);
session_set_cookie_params(604800);
session_start();


/**
 * Load helpers
 */

require __DIR__ . '/helpers.php';