<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Data;

/**
 * Class Set
 * @package Craft\Data
 *
 * Array utils functions
 */
abstract class Set
{

    /**
     * Silent get
     * @param array $array
     * @param string $key
     * @return bool
     */
    public static function has(array $array, $key)
    {
        return isset($array[$key]);
    }

    /**
     * Silent get
     * @param array $array
     * @param string $key
     * @return null|mixed
     */
    public static function get(array $array, $key)
    {
        return isset($array[$key]) ? $array[$key] : null;
    }


    /**
     * Get first element
     * @param array $array
     * @return mixed
     */
    public static function first(array $array)
    {
        return reset($array);
    }


    /**
     * Get first key
     * @param array $array
     * @return string
     */
    public static function firstKey(array $array)
    {
        reset($array);
        return key($array);
    }


    /**
     * Get last element
     * @param array $array
     * @return mixed
     */
    public static function last(array $array)
    {
        return end($array);
    }


    /**
     * Get last key
     * @param array $array
     * @return string
     */
    public static function lastKey(array $array)
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
    public static function keyOf(array $array, $value)
    {
        return array_search($value, $array);
    }


    /**
     * Find all keys of matched value
     * @param array $array
     * @param mixed $value
     * @return array
     */
    public static function keysOf(array $array, $value)
    {
        return array_keys($array, $value);
    }


    /**
     * Replace all value
     * @param array $array
     * @param mixed $value
     * @param mixed $replacement
     * @return array
     */
    public static function replace(array $array, $value, $replacement)
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
    public static function replaceKey(array $array, $key, $replacement)
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
    public static function drop(array $array, $value)
    {
        $keys = array_keys($array, $value);
        return array_diff_key($array, array_flip($keys));
    }


    /**
     * Get keys
     * @param array $array
     * @return array
     */
    public static function keys(array $array)
    {
        return array_keys($array);
    }


    /**
     * Get values
     * @param array $array
     * @return array
     */
    public static function values(array $array)
    {
        return array_values($array);
    }


    /**
     * Insert element at specific position
     * @param array $array
     * @param mixed $value
     * @param string $at
     * @return array
     */
    public static function insert(array $array, $value, $at)
    {
        $before = array_slice($array, 0, $at);
        $after = array_slice($array, $at);
        $before[] = $value;
        return array_merge($before, array_values($after));
    }


    /**
     * Filter values
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function filter(array $array, callable $callback)
    {
        return array_map($array, $callback);
    }


    /**
     * Filter keys
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function filterKey(array $array, callable $callback)
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
    public static function random(array $array, $num = 1)
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
    public static function sort(array $array, array $by)
    {
        // resolve sorting
        $sort = [];
        foreach($by as $key => $val) {
            if(is_int($key)) {
                $sort[$val] = SORT_ASC;
            }
            else {
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

}