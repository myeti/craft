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

    /** @var Mog\Context */
    protected static $context;


    /**
     * Get root
     * @param mixed $set
     * @return string
     */
    public static function root($set = null)
    {
        if(!is_null($set)) {
            static::context()->root = $set;
        }

        return static::context()->root;
    }


    /**
     * Get path
     * @return string
     */
    public static function path()
    {
        $ctx = static::context();
        return call_user_func_array([$ctx, 'path'], func_get_args());
    }


    /**
     * Get http code
     * @param mixed $set
     * @return string
     */
    public static function code($set = null)
    {
        if(!is_null($set)) {
            static::context()->code = $set;
        }

        return static::context()->code;
    }


    /**
     * Is https
     * @param mixed $set
     * @return string
     */
    public static function https($set = null)
    {
        if(!is_null($set)) {
            static::context()->https = $set;
        }

        return static::context()->https;
    }


    /**
     * Is http
     * @param mixed $set
     * @return string
     */
    public static function http($set = null)
    {
        if(!is_null($set)) {
            static::context()->http = $set;
        }

        return static::context()->http;
    }


    /**
     * Get protocol
     * @param mixed $set
     * @return string
     */
    public static function protocol($set = null)
    {
        if(!is_null($set)) {
            static::context()->protocol = $set;
        }

        return static::context()->protocol;
    }


    /**
     * Get method
     * @param mixed $set
     * @return string
     */
    public static function method($set = null)
    {
        if(!is_null($set)) {
            static::context()->method = $set;
        }

        return static::context()->method;
    }


    /**
     * Is async
     * @param mixed $set
     * @return string
     */
    public static function async($set = null)
    {
        if(!is_null($set)) {
            static::context()->async = $set;
        }

        return static::context()->async;
    }


    /**
     * Is sync
     * @param mixed $set
     * @return string
     */
    public static function sync($set = null)
    {
        if(!is_null($set)) {
            static::context()->sync = $set;
        }

        return static::context()->sync;
    }


    /**
     * Get browser
     * @param mixed $set
     * @return string
     */
    public static function browser($set = null)
    {
        if(!is_null($set)) {
            static::context()->browser = $set;
        }

        return static::context()->browser;
    }


    /**
     * Get mobile
     * @param mixed $set
     * @return string
     */
    public static function mobile($set = null)
    {
        if(!is_null($set)) {
            static::context()->mobile = $set;
        }

        return static::context()->mobile;
    }


    /**
     * Get host
     * @param mixed $set
     * @return string
     */
    public static function host($set = null)
    {
        if(!is_null($set)) {
            static::context()->host = $set;
        }

        return static::context()->host;
    }


    /**
     * Get url
     * @return string
     */
    public static function url()
    {
        $ctx = static::context();
        return call_user_func_array([$ctx, 'url'], func_get_args());
    }


    /**
     * Get query
     * @param mixed $set
     * @return mixed|string
     */
    public static function query($set = null)
    {
        if(!is_null($set)) {
            static::context()->query = $set;
        }

        return static::context()->query;
    }


    /**
     * Get base
     * @param mixed $set
     * @return string
     */
    public static function base($set = null)
    {
        if(!is_null($set)) {
            static::context()->base = $set;
        }

        return static::context()->base;
    }


    /**
     * Get full url
     * @param mixed $set
     * @return string
     */
    public static function fullurl($set = null)
    {
        if(!is_null($set)) {
            static::context()->fullurl = $set;
        }

        return static::context()->fullurl;
    }


    /**
     * Get url from
     * @param mixed $set
     * @return mixed|string
     */
    public static function from($set = null)
    {
        if(!is_null($set)) {
            static::context()->from = $set;
        }

        return static::context()->from;
    }


    /**
     * Get ip
     * @param mixed $set
     * @return mixed|string
     */
    public static function ip($set = null)
    {
        if(!is_null($set)) {
            static::context()->ip = $set;
        }

        return static::context()->ip;
    }


    /**
     * Is local
     * @param mixed $set
     * @return bool
     */
    public static function local($set = null)
    {
        if(!is_null($set)) {
            static::context()->local = $set;
        }

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
    public static function get($key, $fallback = null)
    {
        return static::context()->get($key, $fallback);
    }


    /**
     * $_GET values
     * @return array
     */
    public static function gets()
    {
        return static::context()->gets();
    }


    /**
     * $_POST value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public static function post($key, $fallback = null)
    {
        return static::context()->post($key, $fallback);
    }


    /**
     * $_POST values
     * @return array
     */
    public static function posts()
    {
        return static::context()->posts();
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
        return static::context()->files();
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
        return static::context()->servers();
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
        return static::context()->headers();
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
        return static::context()->envs();
    }


    /**
     * Set env value
     * @param string $env
     * @param string $value
     * @return mixed
     */
    public static function set($env, $value)
    {
        static::context()->set($env, $value);
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
     * @return Mog\Context
     */
    public static function context()
    {
        if(!static::$context) {
            static::$context = new Mog\Context;
        }

        return static::$context;
    }

}