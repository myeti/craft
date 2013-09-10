<?php

namespace craft\session;

abstract class Env
{

    /**
     * Retrieve data from context
     * @param $label
     * @return null
     */
    public static function get($label)
    {
        return static::has($label)
            ? $_ENV['craft'][$label]
            : null;
    }


    /**
     * Store data in context
     * @param $label
     * @param $value
     */
    public static function set($label, $value)
    {
        $_ENV['craft'][$label] = $value;
    }


    /**
     * Check if data exists in context
     * @param $label
     * @return bool
     */
    public static function has($label)
    {
        return isset($_ENV['craft'][$label]);
    }

}