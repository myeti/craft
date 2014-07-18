<?php
/**
 * This file is part of the Forge package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Forge;

use Craft\Reflect\ClassLoader;

abstract class Autoloader
{

    /** @var ClassLoader */
    protected $loader;


    /**
     * Get ClassLoader instance
     * @return ClassLoader
     */
    protected static function instance()
    {
        static $instance;
        if(!$instance) {
            $instance = new ClassLoader;
        }

        return $instance;
    }


    /**
     * Register vendor path
     * @param string $prefix
     * @param string $path
     */
    public static function vendor($prefix, $path)
    {
        static::instance()->vendor($prefix, $path);
    }


    /**
     * Register many vendors path
     * @param array $vendors
     */
    public static function vendors(array $vendors)
    {
        static::instance()->vendors($vendors);
    }


    /**
     * Register alias
     * @param string $alias
     * @param string $class
     */
    public static function alias($alias, $class)
    {
        static::instance()->alias($alias, $class);
    }


    /**
     * Register many aliases
     * @param array $aliases
     */
    public static function aliases(array $aliases)
    {
        static::instance()->aliases($aliases);
    }


    /**
     * Auto-register as Autoloader
     */
    public static function register()
    {
        static::instance()->register();
    }


    /**
     * Load a class
     * @param string $class
     * @throws \RuntimeException
     * @return bool
     */
    public static function load($class)
    {
        return static::instance()->load($class);
    }


    /**
     * Get vendor path
     * @param string $vendor
     * @throws \RuntimeException
     * @return string
     */
    public static function path($vendor)
    {
        return static::instance()->path($vendor);
    }

}