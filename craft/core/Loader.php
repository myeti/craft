<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\core;

abstract class Loader
{

    /** @var array */
    protected static $_vendors = [];


    /**
     * Register vendor path
     * @param string $prefix
     * @param string $path
     */
    public static function vendor($prefix, $path)
    {
        // clean
        $prefix = trim($prefix, '\\');
        $path = str_replace('\\', DIRECTORY_SEPARATOR , $path);
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        // register
        static::$_vendors[$prefix] = $path;
    }


    /**
     * Register many vendors path
     * @param array $vendors
     */
    public static function vendors(array $vendors)
    {
        foreach($vendors as $prefix => $path) {
            static::vendor($prefix, $path);
        }
    }


    /**
     * Register alias
     * @param string $alias
     * @param string $class
     */
    public static function alias($alias, $class)
    {
        class_alias($class, $alias);
    }


    /**
     * Register many aliases
     * @param array $aliases
     */
    public static function aliases(array $aliases)
    {
        foreach($aliases as $alias => $class) {
            static::alias($alias, $class);
        }
    }


    /**
     * Auto-register as Autoloader
     */
    public static function register()
    {
        spl_autoload_register('static::load');
    }


    /**
     * Load a class
     * @param string $class
     * @return bool
     */
    public static function load($class)
    {
        // clean
        $class = str_replace('\\', DIRECTORY_SEPARATOR , $class);
        $class .= '.php';

        // has vendor ?
        foreach(static::$_vendors as $vendor => $path) {

            // prefix matching
            $length = strlen($vendor);
            if(substr($class, 0, $length) === $vendor) {

                // make real path
                $filename = $path . substr($class, $length);

                // error
                if(!file_exists($filename)) {
                    throw new \RuntimeException('Class "' . $filename . '" does not exist.');
                }

                // load class
                require $filename;
                return true;
            }

        }

        return false;
    }


    /**
     * Get vendor path
     * @param string $vendor
     * @return string
     */
    public static function path($vendor)
    {
        // clean
        $vendor = trim($vendor, '\\');

        // error
        if(!isset(static::$_vendors[$vendor])) {
            throw new \RuntimeException('Vendor "' . $vendor . '" does not exists.');
        }

        return static::$_vendors[$vendor];
    }

}