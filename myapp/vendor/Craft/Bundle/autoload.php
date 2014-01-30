<?php

/**
 * Setup autoloader
 */

require dirname(__DIR__) . '/Service/Loader.php';

$loader = new Craft\Service\Loader();
$loader->register();

$loader->vendors([
    'Craft' => dirname(__DIR__),
    'Psr'   => dirname(dirname(__DIR__)) . '/Psr',
    'My'    => dirname($_SERVER['SCRIPT_FILENAME'])
]);

Craft\Service::loader($loader);


/**
 * Setup dev tools
 */

$logger = new Craft\Service\Logger();
$tracker = new Craft\Service\Tracker();
$tracker->start('craft.app');

Craft\Service::logger($logger);
Craft\Service::tracker($tracker);


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