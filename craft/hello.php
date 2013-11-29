<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */


/*
 * Autoloader
 */

require 'core/Loader.php';

craft\core\Loader::register();

craft\core\Loader::vendors([
    'craft' => __DIR__,
    'my'    => dirname($_SERVER['SCRIPT_FILENAME'])
]);

craft\core\Loader::aliases([

    'craft\Build'       => 'craft\core\builder\Build',
    'craft\Builder'     => 'craft\core\builder\Builder',
    'craft\Route'       => 'craft\core\router\Route',
    'craft\Router'      => 'craft\core\router\Router',
    'craft\View'        => 'craft\core\render\View',
    'craft\Dispatcher'  => 'craft\core\Dispatcher',
    'craft\Loader'      => 'craft\core\Loader',

    'craft\Session'     => 'craft\data\Session',
    'craft\Flash'       => 'craft\data\Flash',
    'craft\Auth'        => 'craft\data\Auth',
    'craft\Env'         => 'craft\data\Env',
    'craft\Bag'         => 'craft\data\Bag',
    'craft\Mog'         => 'craft\data\Mog',

    'craft\Events'      => 'craft\meta\Events',

    'craft\Syn'         => 'craft\db\Syn',
    'craft\Model'       => 'craft\db\Model',

]);


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

craft\data\Session::init();
craft\data\Flash::init();
craft\data\Auth::init();
craft\data\Env::init();


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
    return craft\core\Loader::path($path);
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
 * Read-only Mog
 * @return \craft\data\Mog
 */
function mog()
{
    static $mog;
    if(!$mog){
        $mog = new craft\data\Mog();
    }

    return $mog;
}


/**
 * Read-only Auth
 * @return \stdClass
 */
function auth()
{
    return (object)[
        'logged'    => craft\data\Auth::logged(),
        'rank'      => craft\data\Auth::rank(),
        'user'      => craft\data\Auth::user()
    ];
}


/**
 * Read-only Flash
 * @param string $key
 * @return string
 */
function flash($key)
{
    return craft\data\Flash::get($key);
}