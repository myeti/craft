<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */

/************************************************
 * OBJECT
 */


/**
 * Hydrate object props with array
 * @param $object
 * @param array $data
 * @return object
 */
function hydrate_obj($object, array $values)
{
    foreach($values as $field => $value) {
        if(property_exists($object, $field)) {
            $object->{$field} = $value;
        }
    }

    return $object;
}


/**
 * Get short class name
 * @param string|object $class
 * @return string
 */
function get_classname($class)
{
    if(is_object($class)) {
        $class = get_class($class);
    }

    $segments = explode('\\', $class);
    return end($segments);
}


/**
 * Check if object is using trait
 * @param $object
 * @param $trait
 * @return bool
 */
function class_has_trait($class, $trait)
{
    if(is_object($class)) {
        $class = get_class($class);
    }

    return in_array($trait, class_uses($class));
}



/************************************************
 * CALLABLE
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



/************************************************
 * REQUEST
 */


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



/************************************************
 * RESPONSE
 */


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



/************************************************
 * DEBUG
 */


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



/************************************************
 * TEXT
 */


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



/************************************************
 * ARRAY
 */


/**
 * Check if input is traversable
 * @param $input
 * @return bool
 */
function is_traversable($input)
{
    return (is_array($input) || $input instanceof \Traversable);
}


/**
 * Check if input is a collection
 * @param $input
 * @return bool
 */
function is_collection($input)
{
    return (is_array($input) || $input instanceof \ArrayAccess);
}


/**
 * Check if key exists
 * @param array $array
 * @param string $key
 * @return bool
 */
function array_has(array $array, $key)
{
    return isset($array[$key]);
}


/**
 * Silent get
 * @param array $array
 * @param string $key
 * @return null|mixed
 */
function array_get(array $array, $key)
{
    return isset($array[$key]) ? $array[$key] : null;
}


/**
 * Get first element
 * @param array $array
 * @return mixed
 */
function array_first(array $array)
{
    return reset($array);
}


/**
 * Get first key
 * @param array $array
 * @return string
 */
function array_first_key(array $array)
{
    reset($array);
    return key($array);
}


/**
 * Get last element
 * @param array $array
 * @return mixed
 */
function array_last(array $array)
{
    return end($array);
}


/**
 * Get last key
 * @param array $array
 * @return string
 */
function array_last_key(array $array)
{
    end($array);
    return key($array);
}


/**
 * Find first key of matched value
 * @param array $array
 * @param mixed $value
 * @return int
 */
function array_key(array $array, $value)
{
    return array_search($value, $array);
}


/**
 * Replace all value
 * @param array $array
 * @param mixed $value
 * @param mixed $replacement
 * @return array
 */
function array_replace_value(array $array, $value, $replacement)
{
    $keys = array_keys($array, $value);
    foreach($keys as $key) {
        $array[$key] = $replacement;
    }

    return $array;
}


/**
 * Replace key and keep order
 * @param array $array
 * @param mixed $key
 * @param mixed $replacement
 * @return array|bool
 */
function array_replace_key(array $array, $key, $replacement)
{
    // key does not exists
    if(!isset($array[$key])) {
        return false;
    }

    // search current key
    $keys = array_keys($array);
    $index = array_search($key, $keys);

    // replace
    $keys[$index] = $replacement;
    return array_combine($keys, $array);
}


/**
 * Remove rows with specified value
 * @param array $array
 * @param mixed $value
 * @return array
 */
function array_drop(array $array, $value)
{
    $keys = array_keys($array, $value);
    return array_diff_key($array, array_flip($keys));
}


/**
 * Insert element at specific position
 * @param array $array
 * @param mixed $value
 * @param string $at
 * @return array
 */
function array_insert(array $array, $value, $at)
{
    $before = array_slice($array, 0, $at);
    $after = array_slice($array, $at);
    $before[] = $value;
    return array_merge($before, array_values($after));
}


/**
 * Filter keys
 * @param array $array
 * @param callable $callback
 * @return array
 */
function array_filter_key(array $array, callable $callback)
{
    $keys = array_map(array_keys($array), $callback);
    return array_diff_key($array, array_flip($keys));
}


/**
 * Get random element(s)
 * @param array $array
 * @param int $num
 * @return mixed|array
 */
function array_random(array $array, $num = 1)
{
    $keys = (array)array_rand($array, $num);
    $results = array_intersect_key($array, $keys);

    return ($num == 1) ? current($results) : $results;
}


/**
 * Sort array by columns
 * - [column => SORT_ASC] let you decide
 * - [column1, column2] will sort ASC
 * @param array $array
 * @param array $by
 * @return array
 */
function array_sort(array $array, array $by)
{
    // resolve sorting
    $sort = [];
    foreach($by as $key => $val) {
        if(is_int($key)) {
            $sort[$val] = SORT_ASC;
        } else {
            $sort[$key] = $val;
        }
    }

    // prepare columns
    $columns = [];
    foreach($array as $key => $row) {
        foreach($row as $column => $value) {

            // need sorting ?
            if(isset($sort[$column])) {
                $columns[$column][$key] = $value;
            }

        }
    }

    // prepare args
    $args = [];
    foreach($columns as $name => $keys) {
        $args[] = $keys;
        $args[] = $sort[$name];
    }
    $args[] = $array;

    return array_multisort(...$args);
}


/**
 * Has key with flat search
 * @param array|\ArrayAccess $array
 * @param string $key
 * @throws \InvalidArgumentException
 * @return bool
 */
function array_flat_has($array, $key)
{
    list($item, $last) = array_flat_resolve($array, $key);
    return isset($item[$last]);
}


/**
 * Get value with flat search
 * @param array|\ArrayAccess $array
 * @param string $key
 * @param mixed $fallback
 * @throws \InvalidArgumentException
 * @return mixed
 */
function array_flat_get($array, $key, $fallback = null)
{
    list($item, $last) = array_flat_resolve($array, $key);
    return isset($item[$last]) ? $item[$last] : $fallback;
}


/**
 * Set value with flat search
 * @param array|\ArrayAccess $array
 * @param string $key
 * @param mixed $value
 * @throws \InvalidArgumentException
 */
function array_flat_set(&$array, $key, $value)
{
    list($item, $last) = array_flat_resolve($array, $key, true);
    $item[$last] = $value;
}


/**
 * Unset element with flat search
 * @param array|\ArrayAccess $array
 * @param string $key
 * @throws \InvalidArgumentException
 * @return bool
 */
function array_flat_drop(&$array, $key)
{
    if($resolved = array_flat_resolve($array, $key)) {
        list($item, $last) = $resolved;
        if(isset($item[$last])) {
            unset($item[$last]);
        }
        return true;
    }

    return false;
}


/**
 * Flat resolve
 * @param array $array
 * @param string $key
 * @param bool $dig
 * @param string $separator
 * @throws InvalidArgumentException
 * @return array|bool
 */
function array_flat_resolve(&$array, $key, $dig = false, $separator = '.')
{
    // check
    if(!is_collection($array)) {
        throw new \InvalidArgumentException('Input $array must be a valid array or ArrayAccess.');
    }

    // resolve item
    $key = trim($key, $separator);
    $segments = explode($separator, $key);
    $last = end($segments);

    // one does not simply walk into Mordor
    foreach($segments as $segment) {

        // is last segment ?
        if($segment == $last) {
            break;
        }

        // namespace does not exist
        if(!isset($array[$segment]) and $dig) {
            if($dig) {
                $array[$segment] = [];
            } else {
                return false;
            }
        }

        // next segment
        $array = & $array[$segment];
    }

    return [$array, $last];
}