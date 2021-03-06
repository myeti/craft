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

abstract class Session
{

    /**
     * Get all data
     * @return array
     */
    public static function all()
    {
        return static::storage()->all();
    }

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
        // unserialize
        $value = static::storage()->get($key, $fallback);
        if(is_string($value)) {
            $decrypted = @unserialize($value);
            if($decrypted !== false or $value == 'b:0;') {
                $value = $decrypted;
            }
        }

        return $value;
    }


    /**
     * Store value
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        // serialize
        if(!is_scalar($value)) {
            $value = serialize($value);
        }

        static::storage()->set($key, $value);
    }


    /**
     * Delete data
     * @param string $key
     */
    public static function drop($key)
    {
        static::storage()->drop($key);
    }


    /**
     * Clear all data
     */
    public static function clear()
    {
        static::storage()->clear();
    }


    /**
     * Save data in session
     */
    public static function save()
    {
        static::storage()->save();
    }


    /**
     * Singleton session instance
     * @return Session\Native
     */
    protected static function storage()
    {
        static $instance;
        if(!$instance) {
            $instance = new Session\Native('app/data');
        }

        return $instance;
    }

}