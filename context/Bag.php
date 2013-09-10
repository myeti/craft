<?php

namespace craft\session;

abstract class Bag
{

    /**
     * Retrieve data from context
     * @param $label
     * @return null
     */
    public static function get($label)
    {
        return static::has($label)
            ? $_SESSION['craft.bag'][$label]
            : null;
    }


    /**
     * Store data in context
     * @param $label
     * @param $value
     */
    public static function set($label, $value)
    {
        $_SESSION['craft.bag'][$label] = $value;
    }


    /**
     * Check if data exists in context
     * @param $label
     * @return bool
     */
    public static function has($label)
    {
        return isset($_SESSION['craft.bag'][$label]);
    }


    /**
     * Clear a slot or all context
     */
    public static function clear($label = null)
    {
        if($label){
            unset($_SESSION['craft.bag'][$label]);
        }
        else {
            $_SESSION['craft.bag'] = [];
        }
    }

}