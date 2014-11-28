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
    return \Craft\Kit\Object::classname($object);
}


/**
 * Check if object is using trait
 * @param $object
 * @param $trait
 * @return bool
 */
function has_trait($object, $trait)
{
    return \Craft\Kit\Object::hasTrait($object, $trait);
}


/**
 * Hydrate object props with array
 * @param $object
 * @param array $data
 * @return object
 */
function hydrate($object, array $data)
{
    return \Craft\Kit\Object::hydrate($object, $data);
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
function redirect(...$segments)
{
    header('Location: ' . url(...$segments));
    exit;
}


/**
 * Debug var
 * @param $vars
 */
function boom(...$vars)
{
    \Craft\Debug\Bada::boom(...$vars);
}


/**
 * Alias of boom()
 * @param $vars
 */
function dd(...$vars)
{
    \Craft\Debug\Bada::boom(...$vars);
}


/**
 * Debug with steps
 * @param string $message
 */
function ds($message = 'step')
{
    \Craft\Debug\Bada::step($message);
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
    return Craft\Box\Mog::value($key) ?: $fallback;
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
function str_compose($string, array $vars = [])
{
    return Craft\Data\Text\String::compose($string, $vars);
}


/**
 * Alias of Regex::match()
 * @param string $string
 * @param string $pattern
 * @return bool
 */
function str_match($string, $pattern)
{
    return Craft\Data\Text\Regex::match($string, $pattern);
}


/**
 * Alias of Regex::wildcard()
 * @param string $string
 * @param string $pattern
 * @return bool
 */
function str_is($string, $pattern)
{
    return Craft\Data\Text\Regex::wildcard($string, $pattern);
}


/**
 * Generate redirect response
 * @param string $segments
 * @return \Craft\App\Response
 */
function go(...$segments)
{
    $url = implode('/', $segments);
    return \Craft\App\Response::redirect($url);
}


/**
 * Generate redirect response away
 * @param string $segments
 * @return \Craft\App\Response
 */
function away(...$segments)
{
    $url = implode('/', $segments);
    return \Craft\App\Response::redirect($url, true);
}