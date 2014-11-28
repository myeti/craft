<?php

/**
 * Resolve environment
 */

// default : prod
$env = 'prod';

// forced from htaccess
if(getenv('__ENV__')) {
    $env = strtolower(getenv('__ENV__'));
}
// local access : dev
elseif(in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    $env = 'dev';
}

// define global env name
define('__ENV__', $env);

// load vendor bootstrap
require_once '../vendor/autoload.php';

// load app bootstrap
require_once '../app/' . $env . '.php';