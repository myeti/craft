<?php

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
     * @return Session\Storage
     */
    protected static function storage()
    {
        static $instance;
        if(!$instance) {
            $instance = new Session\Storage('app/flash');
        }

        return $instance;
    }

}