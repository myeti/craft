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
 * Check if input is traversable
 * @param $input
 * @return bool
 */
function is_traversable($input)
{
    return (is_array($input) or $input instanceof \Traversable);
}


/**
 * Check if input is a collection
 * @param $input
 * @return bool
 */
function is_collection($input)
{
    return (is_array($input) or $input instanceof \ArrayAccess);
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
    return array_intersect_key($array, $keys);
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

    return call_user_func_array('array_multisort', $args);
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
        unset($item[$last]);
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