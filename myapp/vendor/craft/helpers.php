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
    $root = rtrim(craft\kit\loader\Loader::path('my'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    return $root . ltrim(implode(DIRECTORY_SEPARATOR, $segments), DIRECTORY_SEPARATOR);
}


/**
 * Get complete url
 * "Ce truc, ça fait les frites !" - Rudy
 * @return string
 */
function url()
{
    $segments = func_get_args();
    return rtrim(craft\box\env\Mog::base(), '/') . '/' . ltrim(implode('/', $segments), '/');
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
    return craft\box\env\Mog::post($key, $fallback);
}


/**
 * Read-only Auth
 * @return \stdClass
 */
function auth()
{
    return (object)[
        'logged'    => craft\box\env\Auth::logged(),
        'rank'      => craft\box\env\Auth::rank(),
        'user'      => craft\box\env\Auth::user()
    ];
}


/**
 * Read-only Flash
 * @param string $key
 * @return string
 */
function flash($key)
{
    return craft\box\env\Flash::pull($key);
}


/**
 * Alias of craft\I18n::translate()
 * @param  string $text
 * @param  array $vars
 * @return string
 */
function __($text, array $vars = [])
{
    return craft\box\text\I18n::translate($text, $vars);
}


/**
 * Hydrate object with env
 * @param $object
 * @param array $data
 * @return object
 */
function hydrate($object, array $data)
{
    return craft\box\meta\Object::hydrate($object, $data);
}


/**
 * Return object instance for chaining
 * @param $object
 * @return object
 */
function with($object)
{
    return craft\box\meta\Object::with($object);
}


/**
 * Get first element of array
 * @param $array
 * @return mixed
 */
function array_first(array &$array)
{
    return craft\box\data\Collection::first($array);
}


/**
 * Get last element of array
 * @param $array
 * @return mixed
 */
function array_last(array &$array)
{
    return craft\box\data\Collection::last($array);
}


/**
 * Get value or null, don't throw error
 * @param $array
 * @param $key
 * @return null
 */
function array_get_silent($array, $key)
{
    return craft\box\data\Collection::get($array, $key);
}


/**
 * Sort array by column names and directions
 * Ex : $sorted = array_sort($array, ['name' => SORT_DESC]);
 * @param array $array
 * @param array $set
 * @return mixed
 */
function array_sort(array $array, array $set)
{
    return craft\box\data\Collection::sort($array, $set);
}


/**
 * Alias of Strong::compose()
 * @param $string
 * @param array $vars
 * @return mixed
 */
function compose($string, array $vars = [])
{
    return craft\box\text\String::compose($string, $vars);
}


/**
 * Return input reference
 * @param $something
 * return mixed
 */
function ref(&$something)
{
    return $something;
}