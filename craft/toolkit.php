<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 *
 * @author Aymeric Assier <aymeric.assier@gmail.com>
 * @date 2013-09-12
 * @version 0.1
 */

/*
 * Autoloader
 */
require 'Loader.php';
spl_autoload_register('craft\Loader::load');
craft\Loader::vendor('craft', __DIR__);
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

craft\Auth::init();


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
 * @param      $key
 * @param null $value
 * @return null
 */
function session($key, $value = null)
{
    if(is_null($value)){
        return isset($_SESSION['craft.data'][$key]) ? $_SESSION['craft.data'][$key] : null;
    }
    else {
        $_SESSION['craft.data'][$key] = $value;
    }
}


/**
 * Flash helper
 * @param      $key
 * @param null $value
 * @return null
 */
function flash($key, $value = null)
{
    if(is_null($value)){
        $message = isset($_SESSION['craft.flash'][$key]) ? $_SESSION['craft.flash'][$key] : null;
        unset($_SESSION['craft.flash'][$key]);
        return $message;
    }
    else {
        $_SESSION['craft.flash'][$key] = $value;
    }
}


/**
 * Env/Config helper
 * @param      $key
 * @param null $value
 * @return null
 */
function env($key, $value = null)
{
    if(is_null($value)){
        return isset($_SESSION['craft.env'][$key]) ? $_SESSION['craft.env'][$key] : null;
    }
    else {
        $_SESSION['craft.env'][$key] = $value;
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


/**
 * Context helper
 * @param null $key
 * @param null $value
 * @return \craft\Context
 */
function mog($key = null, $value = null)
{
    static $mog;
    if(!$mog){
        $mog = new craft\Mog();
    }

    // bag set
    if($key and !is_null($value)){
        $mog[$key] = $value;
        return $mog;
    }
    // bag get
    elseif($key and is_null($value)){
        return $mog[$key];
    }

    return $mog;
}


/**
 * Auth helper : read only
 * @return \stdClass
 */
function auth()
{
    static $std;
    if(!$std){
        $std = new \stdClass();
    }

    // update data
    $std->logged = craft\Auth::logged();
    $std->rank = craft\Auth::rank();
    $std->user = craft\Auth::user();

    return $std;
}