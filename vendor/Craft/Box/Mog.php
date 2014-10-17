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

abstract class Mog
{

    /** @var Context */
    protected static $context;


    /**
     * Get root
     * @return string
     */
    public static function root()
    {
        return static::context()->root;
    }


    /**
     * Get path
     * @return string
     */
    public static function path(...$args)
    {
        return static::context()->path(...$args);
    }


    /**
     * Get http code
     * @return string
     */
    public static function code()
    {
        return static::context()->http->code;
    }


    /**
     * Is https
     * @return string
     */
    public static function secure()
    {
        return static::context()->http->secure;
    }


    /**
     * Get method
     * @return string
     */
    public static function method()
    {
        return static::context()->http->method;
    }


    /**
     * Is async
     * @return string
     */
    public static function ajax()
    {
        return static::context()->http->ajax;
    }


    /**
     * Get browser
     * @return string
     */
    public static function browser()
    {
        return static::context()->browser;
    }


    /**
     * Get mobile
     * @return string
     */
    public static function mobile()
    {
        return static::context()->mobile;
    }


    /**
     * Get url
     * @return string
     */
    public static function url(...$args)
    {
        return static::context()->url(...$args);
    }


    /**
     * Get query
     * @return mixed|string
     */
    public static function query()
    {
        return static::context()->url->query;
    }


    /**
     * Get base
     * @return string
     */
    public static function base()
    {
        return static::context()->url->base;
    }


    /**
     * Get url from
     * @return mixed|string
     */
    public static function from()
    {
        return static::context()->url->from;
    }


    /**
     * Get ip
     * @return mixed|string
     */
    public static function ip()
    {
        return static::context()->ip;
    }


    /**
     * Cli mode
     * @return bool
     */
    public static function cli()
    {
        return static::context()->cli;
    }


    /**
     * Is local
     * @return bool
     */
    public static function local()
    {
       return static::context()->local;
    }


    /**
     * Get time
     * @param mixed $set
     * @return mixed|string
     */
    public static function time($set = null)
    {
        if(!is_null($set)) {
            static::context()->time = $set;
        }

        return static::context()->time;
    }


    /**
     * Get timezone
     * @param mixed $set
     * @return string
     */
    public static function timezone($set = null)
    {
        if(!is_null($set)) {
            static::context()->timezone = $set;
        }

        return static::context()->timezone;
    }


    /**
     * Get locale
     * @param mixed $set
     * @return string
     */
    public static function locale($set = null)
    {
        if(!is_null($set)) {
            static::context()->locale = $set;
        }

        return static::context()->locale;
    }


    /**
     * $_GET value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public static function arg($key, $fallback = null)
    {
        return static::context()->arg($key, $fallback);
    }


    /**
     * $_GET values
     * @return array
     */
    public static function args()
    {
        return static::context()->args;
    }


    /**
     * $_POST value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public static function form($key, $fallback = null)
    {
        return static::context()->form($key, $fallback);
    }


    /**
     * $_POST values
     * @return array
     */
    public static function forms()
    {
        return static::context()->form;
    }


    /**
     * $_FILES value
     * @param  string $key
     * @param  string $fallback
     * @return array|object
     */
    public static function file($key, $fallback = null)
    {
        return static::context()->file($key, $fallback);
    }


    /**
     * $_FILES values
     * @return array
     */
    public static function files()
    {
        return static::context()->files;
    }


    /**
     * $_SERVER value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public static function server($key, $fallback = null)
    {
        return static::context()->server($key, $fallback);
    }


    /**
     * $_SERVER values
     * @return array
     */
    public static function servers()
    {
        return static::context()->server;
    }


    /**
     * Headers value
     * @param string $key
     * @param null $fallback
     * @return mixed
     */
    public static function header($key, $fallback = null)
    {
        return static::context()->header($key, $fallback);
    }


    /**
     * Header values
     * @return array
     */
    public static function headers()
    {
        return static::context()->header;
    }


    /**
     * Get env value
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    public static function env($key, $fallback = null)
    {
        return static::context()->env($key, $fallback);
    }


    /**
     * $_GET values
     * @return array
     */
    public static function envs()
    {
        return static::context()->env;
    }


    /**
     * Set env mode (dev, test, prod)
     * @param string $mode
     * @return mixed
     */
    public static function mode($mode)
    {
        static::context()->mode($mode);
    }


    /**
     * Check env mode
     * @param string $mode
     * @return bool
     */
    public static function in($mode)
    {
        return static::context()->in($mode);
    }


    /**
     * Kupo !
     * @return string
     */
    public static function kupo()
    {
        return static::context()->kupo();
    }


    /**
     * Create context wrapper
     * @return Context
     */
    public static function context()
    {
        if(!static::$context) {
            static::$context = Context::create();
        }

        return static::$context;
    }

}