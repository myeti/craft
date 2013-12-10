<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */


/**
 * Get absolute path
 * @return string
 */
function path()
{
    $segments = func_get_args();
    $root = rtrim(craft\Loader::path('my'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    return $root . ltrim(implode(DIRECTORY_SEPARATOR, $segments), DIRECTORY_SEPARATOR);
}


/**
 * Get complete url
 * "Ce truc ça fait les frites !" - Rudy
 * @return string
 */
function url()
{
    $segments = func_get_args();
    return rtrim(APP_URL, '/') . '/' . ltrim(implode('/', $segments), '/');
}


/**
 * Redirect to url
 */
function go()
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
 * @param string $fallback
 * @return null
 */
function post($key = null, $fallback = null)
{
    return craft\Mog::post($key, $fallback);
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
 * Read-only Auth
 * @return \stdClass
 */
function auth()
{
    return (object)[
        'logged'    => craft\Auth::logged(),
        'rank'      => craft\Auth::rank(),
        'user'      => craft\Auth::user()
    ];
}


/**
 * Read-only Flash
 * @param string $key
 * @return string
 */
function flash($key)
{
    return craft\Flash::get($key);
}
