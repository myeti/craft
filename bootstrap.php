<?php
/**
 * This file is part of the Wicked package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 *
 * - - - - - - - - - - - - - -
 *
 * Bootstrat : DO NOT CHANGE ANYTHING !!
 *
 * @author Aymeric Assier <aymeric.assier@gmail.com>
 * @date 2013-05-02
 * @version 0.1
 */

/*
 * Autoloader
 */
require 'Loader.php';
spl_autoload_register('craft\Loader::load');
craft\Loader::vendor('craft', dirname(__FILE__));
craft\Loader::vendor('my', dirname($_SERVER['SCRIPT_FILENAME']));

/*
 * Define base url
 */
define('APP_URL', dirname($_SERVER['SCRIPT_NAME']) . '/');

/*
 * Init context
 */
ini_set('session.use_trans_sid', 0);
ini_set('session.use_only_cookies', 1);
ini_set("session.cookie_lifetime", 604800);
ini_set("session.gc_maxlifetime", 604800);
session_set_cookie_params(604800);
session_start();


/*
 * Functions
 */

/**
 * Get registered path
 * @param $path
 * @return string
 */
function path($path)
{
    return craft\Loader::path($path);
}


/**
 * Get complete url
 * @return string
 */
function url()
{
    $segments = func_get_args();
    return rtrim(APP_URL, '/') . '/' . ltrim(implode('/', $segments), '/');
}


/**
 * Redirect to url
 * @param $url
 */
function go($url)
{
    $segments = func_get_args();
    header('Location: ' . call_user_func_array('url', $segments));
    exit;
}


/**
 * Debug var
 */
function debug()
{
    die(call_user_func_array('var_dump', func_get_args()));
}


/**
 * Session helper
 * @param $name
 * @param null $value
 * @return mixed
 */
function session($name, $value = null)
{
    if(is_null($value)){
        return craft\session\Data::get($name);
    }
    else {
        craft\session\Data::set($name, $value);
    }
}


/**
 * Flash helper
 * @param $name
 * @param null $value
 * @return string
 */
function flash($name, $value = null)
{
    if(is_null($value)){
        return craft\session\Flash::get($name);
    }
    else {
        craft\session\Flash::set($name, $value);
    }
}


/**
 * Log in and out
 * @param $prop
 * @return mixed
 */
function auth($prop = null)
{
    switch($prop){
        case 'logged':
            return craft\session\Auth::logged();
        case 'user':
            return craft\session\Auth::user();
        case 'rank':
            return craft\session\Auth::rank();
        case null:
            return craft\session\Auth::logged();
        default:
            return null;
    }
}


/**
 * Post helper
 * @param null $key
 * @return null
 */
function post($key = null)
{
    if(!$key)
        return $_POST;

    return isset($_POST[$key]) ? $_POST[$key] : null;
}


/**
 * Hydrate object with data
 * @param $object
 * @param array $data
 * @param bool $force
 */
function hydrate(&$object, array $data, $force = false)
{
    foreach($data as $field => $value)
        if($force or (!$force and property_exists($object, $field)))
            $object->{$field} = $value;
}