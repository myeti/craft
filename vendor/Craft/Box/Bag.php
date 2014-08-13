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

use Craft\Data\Provider;
use Craft\Data\ProviderInterface;

abstract class Bag
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
        return static::storage()->get($key, $fallback);
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
     * Singleton instance
     * @return ProviderInterface
     */
    protected static function storage()
    {
        static $instance;
        if(!$instance) {
            $instance = new Provider;
        }

        return $instance;
    }

}