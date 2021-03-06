<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box;

abstract class Flash
{

    /**
     * Check if data exists
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return static::storage()->has($key);
    }


    /**
     * Get data
     * @param $key
     * @param mixed $fallback
     * @return mixed
     */
    public static function get($key, $fallback = null)
    {
        $value = static::storage()->get($key, $fallback);
        static::storage()->drop($key);
        return $value;
    }


    /**
     * Store value
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        static::storage()->set($key, $value);
    }


    /**
     * Clear all data
     */
    public static function clear()
    {
        static::storage()->clear();
    }


    /**
     * Singleton session instance
     * @return Session\Native
     */
    protected static function storage()
    {
        static $instance;
        if(!$instance) {
            $instance = new Session\Native('app/flash');
        }

        return $instance;
    }

}