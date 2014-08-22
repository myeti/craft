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
 * Class Flat
 * @package Craft\Data
 *
 * Flat search in array
 * ex : key1.key2.key3 = [key1 => [key2 => [key3 => value]]]
 */
abstract class ArrayFlat
{

    public static $separator = '.';


    /**
     * Has key with flat search
     * @param array|\ArrayAccess $array
     * @param string $key
     * @throws \InvalidArgumentException
     * @return bool
     */
    public static function has($array, $key)
    {
        // check
        if(!is_array($array) and !($array instanceof \ArrayAccess)) {
            throw new \InvalidArgumentException('Input $array must be a valid array or ArrayAccess.');
        }

        list($item, $last) = static::resolve($array, $key);
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
    public static function get($array, $key, $fallback = null)
    {
        // check
        if(!is_array($array) and !($array instanceof \ArrayAccess)) {
            throw new \InvalidArgumentException('Input $array must be a valid array or ArrayAccess.');
        }

        list($item, $last) = static::resolve($array, $key);
        return isset($item[$last]) ? $item[$last] : $fallback;
    }


    /**
     * Set value with flat search
     * @param array|\ArrayAccess $array
     * @param string $key
     * @param mixed $value
     * @throws \InvalidArgumentException
     */
    public static function set(&$array, $key, $value)
    {
        // check
        if(!is_array($array) and !($array instanceof \ArrayAccess)) {
            throw new \InvalidArgumentException('Input $array must be a valid array or ArrayAccess.');
        }

        list($item, $last) = static::resolve($array, $key, true);
        $item[$last] = $value;
    }


    /**
     * Unset element with flat search
     * @param array|\ArrayAccess $array
     * @param string $key
     * @throws \InvalidArgumentException
     * @return bool
     */
    public static function drop(&$array, $key)
    {
        // check
        if(!is_array($array) and !($array instanceof \ArrayAccess)) {
            throw new \InvalidArgumentException('Input $array must be a valid array or ArrayAccess.');
        }

        if($resolved = static::resolve($array, $key)) {
            list($item, $last) = $resolved;
            unset($item[$last]);
            return true;
        }

        return false;
    }


    /**
     * Flat search
     * @param array $array
     * @param string $key
     * @param bool $dig
     * @return array|bool
     */
    protected static function resolve(&$array, $key, $dig = false)
    {
        // resolve item
        $key = trim($key, static::$separator);
        $segments = explode(static::$separator, $key);
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

} 