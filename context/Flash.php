<?php

namespace craft\session;

abstract class Flash
{

    /**
     * Retrieve flash message from context
     * @param $name
     * @return null
     */
    public static function get($name)
    {
        $message = static::has($name)
            ? $_SESSION['craft.flash'][$name]
            : false;

        unset($_SESSION['craft.flash'][$name]);

        return $message;
    }


    /**
     * Store flash message in context
     * @param $name
     * @param $message
     */
    public static function set($name, $message)
    {
        $_SESSION['craft.flash'][$name] = $message;
    }


    /**
     * Check if flash message exists in context
     * @param $name
     * @return bool
     */
    public static function has($name)
    {
        return isset($_SESSION['craft.flash'][$name]);
    }

}