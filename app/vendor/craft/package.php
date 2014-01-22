<?php

/**
 * Hello :)
 */

// get loader class
require 'kit/loader/Loader.php';

// set as official autoloader
craft\kit\loader\Loader::register();

// alias itself, for simplicity
craft\kit\loader\Loader::alias('craft\Loader', 'craft\kit\loader\Loader');

// add craft, and the project in the vendor mapper
craft\kit\loader\Loader::vendors([
    'craft' => __DIR__,
    'my'    => dirname($_SERVER['SCRIPT_FILENAME'])
]);

// initialize session & cookie
ini_set('session.use_trans_sid', 0);
ini_set('session.use_only_cookies', 1);
ini_set("session.cookie_lifetime", 604800);
ini_set("session.gc_maxlifetime", 604800);
session_set_cookie_params(604800);
session_start();

// load helper
require 'helpers.php';