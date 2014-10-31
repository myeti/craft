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
 * Get class name
 * @param $object
 * @return string
 */
function classname($object)
{
    if(is_object($object)) {
        $object = get_class($object);
    }

    $segments = explode('\\', $object);
    return end($segments);
}


/**
 * Check if object is using trait
 * @param $object
 * @param $trait
 * @return bool
 */
function has_trait($object, $trait)
{
    return in_array($trait, class_uses($object));
}


/**
 * Hydrate object props with array
 * @param $object
 * @param array $data
 * @return object
 */
function hydrate($object, array $data)
{
    foreach($data as $field => $value) {
        if(property_exists($object, $field)) {
            $object->{$field} = $value;
        }
    }

    return $object;
}


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
function path(...$segments)
{
    return \Craft\Box\Mog::path(...$segments);
}


/**
 * Get complete url
 * "Ce truc, ça fait les frites !" - Rudy
 * @param string $somewhere
 * @return string
 */
function url(...$segments)
{
    return \Craft\Box\Mog::url(...$segments);
}


/**
 * Redirect to url
 * @param string $somewhere
 */
function go(...$segments)
{
    header('Location: ' . url(...$segments));
    exit;
}


/**
 * Debug var
 * @param mixed $something
 */
function debug(...$args)
{
    var_dump(...$args);
    exit;
}


/**
 * Alias of debug()
 * @param $args
 */
function dd(...$args)
{
    debug(...$args);
}


/**
 * Debug with steps
 * @param string $message
 */
function ds($message = 'step')
{
    static $steps;
    if(!$steps) {
        $steps = [];
    }
    if(!isset($steps[$message])) {
        $steps[$message] = 1;
    }

    var_dump($message .':' . $steps[$message]++);
}


/**
 * Debug var in log
 * @param mixed $something
 */
function log_debug(...$args)
{
    foreach($args as $arg) {
        \Craft\Debug\Logger::debug($arg);
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
    return Craft\Data\Text\Lang::translate($text, $vars);
}


/**
 * Alias of String::compose()
 * @param $string
 * @param array $vars
 * @return mixed
 */
function compose($string, array $vars = [])
{
    return Craft\Data\Text\String::compose($string, $vars);
}