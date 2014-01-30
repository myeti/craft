<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Context;

use Craft\Box\Data\ArrayProvider;
use Craft\Box\Data\StaticProvider;

abstract class Bag extends StaticProvider
{

    /**
     * Create provider instance
     * @return ArrayProvider
     */
    protected static function createInstance()
    {
        return new ArrayProvider();
    }

    /**
     * Retrieve value and execute if callback
     * @param $key
     * @param array $args
     * @return mixed
     */
    public static function make($key, array $args = [])
    {
        // get value
        $fn = static::get($key);

        // execute callback
        if($fn instanceof \Closure) {
            return call_user_func_array($fn,$args);
        }

        return $fn;
    }

    /**
     * Store and retrieve singleton instance
     * @param $key
     * @param callable
     * @return mixed
     */
    public static function singleton($key, \Closure $factory = null)
    {
        // getter
        if(!$factory) {
            return static::make($key);
        }

        // late binding
        static::set($key, function() use($factory) {

            // prepare instance
            static $instance;
            if(!$instance) {
                $instance = $factory();
            }

            return $instance;
        });
    }

}