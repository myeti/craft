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
 * Call action
 * @param callable $action
 * @param array $args
 * @return mixed
 */
function call(callable $action, array $args)
{
    return call_user_func_array($action, $args);
}


/**
 * Get absolute path
 * @return string
 */
function path()
{
    return call('\Craft\Box\Mog::path', func_get_args());
}


/**
 * Get complete url
 * "Ce truc, ça fait les frites !" - Rudy
 * @param string $somewhere
 * @return string
 */
function url($somewhere)
{
    return call('\Craft\Box\Mog::url', func_get_args());
}


/**
 * Redirect to url
 * @param string $somewhere
 */
function go($somewhere)
{
    header('Location: ' . call('url', func_get_args()));
    exit;
}


/**
 * Debug var
 * @param mixed $something
 */
function debug($something)
{
    call('var_dump', func_get_args());
    exit;
}


/**
 * Debug var in log
 * @param mixed $something
 */
function log_debug($something)
{
    foreach(func_get_args() as $arg) {
        \Craft\Trace\Logger::debug($arg);
    }
}


/**
 * Post helper
 * @param string $key
 * @param string $fallback
 * @return mixed
 */
function post($key, $fallback = null)
{
    return Craft\Box\Mog::post($key, $fallback);
}


/**
 * Env helper
 * @param string $key
 * @param string $fallback
 * @return mixed
 */
function env($key, $fallback = null)
{
    return Craft\Box\Mog::env($key, $fallback);
}


/**
 * Alias of Lang::translate()
 * @param  string $text
 * @param  array $vars
 * @return string
 */
function __($text, array $vars = [])
{
    return Craft\Text\Lang::translate($text, $vars);
}


/**
 * Hydrate object with env
 * @param $object
 * @param array $data
 * @return object
 */
function hydrate($object, array $data)
{
    return Craft\Reflect\Object::hydrate($object, $data);
}


/**
 * Alias of String::compose()
 * @param $string
 * @param array $vars
 * @return mixed
 */
function compose($string, array $vars = [])
{
    return Craft\Text\String::compose($string, $vars);
}