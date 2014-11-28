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

use Craft\Http;

abstract class Mog
{

    /** @var Http\Request */
    protected static $request;

    /** @var string */
    protected static $mode = 'prod';


    /**
     * Get code
     * @return int
     */
    public static function code()
    {
        return static::request()->code();
    }


    /**
     * Get method
     * @return string
     */
    public static function method()
    {
        return static::request()->method();
    }


    /**
     * Is secure
     * @return bool
     */
    public static function secure()
    {
        return static::request()->secure();
    }


    /**
     * Is async
     * @return bool
     */
    public static function ajax()
    {
        return static::request()->ajax();
    }


    /**
     * Get url
     * @return string|Http\Url
     */
    public static function url($to = null, ...$segments)
    {
        if($to) {
            $to .= '/' . implode('/', $segments);
            return static::request()->url()->relative($to);
        }

        return static::request()->url();
    }


    /**
     * Get path from root
     * @param string $path
     * @return string
     */
    public static function path(...$path)
    {
        return static::request()->path(...$path);
    }


    /**
     * Get header
     * @return string
     */
    public static function header($name)
    {
        return static::request()->header($name);
    }


    /**
     * Get headers
     * @return array
     */
    public static function headers()
    {
        return static::request()->headers();
    }


    /**
     * Get _server
     * @return string
     */
    public static function server($name)
    {
        return static::request()->server($name);
    }


    /**
     * Get all _server
     * @return array
     */
    public static function servers()
    {
        return static::request()->servers();
    }


    /**
     * Get _env
     * @return string
     */
    public static function env($name)
    {
        return static::request()->env($name);
    }


    /**
     * Get all _env
     * @return array
     */
    public static function envs()
    {
        return static::request()->envs();
    }


    /**
     * Get _get
     * @return string
     */
    public static function param($name)
    {
        return static::request()->param($name);
    }


    /**
     * Get all _get
     * @return array
     */
    public static function params()
    {
        return static::request()->params();
    }


    /**
     * Get _post
     * @return string
     */
    public static function value($name)
    {
        return static::request()->value($name);
    }


    /**
     * Get all _post
     * @return array
     */
    public static function values()
    {
        return static::request()->values();
    }


    /**
     * Get _file
     * @return string
     */
    public static function file($name)
    {
        return static::request()->file($name);
    }


    /**
     * Get all _file
     * @return array
     */
    public static function files()
    {
        return static::request()->files();
    }


    /**
     * Get _cookie
     * @return string
     */
    public static function cookie($name)
    {
        return static::request()->cookie($name);
    }


    /**
     * Get all _cookie
     * @return array
     */
    public static function cookies()
    {
        return static::request()->code();
    }


    /**
     * Set custom data
     * @return Http\Request
     */
    public static function set($name, $value)
    {
        static::request()->set($name, $value);
        return static::request();
    }


    /**
     * Get custom data
     * @return mixed
     */
    public static function get($name)
    {
        return static::request()->get($name);
    }


    /**
     * Get user agent
     * @return string
     */
    public static function agent()
    {
        return static::request()->agent();
    }


    /**
     * Get user ip
     * @return string
     */
    public static function ip()
    {
        return static::request()->ip();
    }


    /**
     * Get user locale
     * @return string
     */
    public static function locale()
    {
        return static::request()->locale();
    }


    /**
     * Get time
     * @return float
     */
    public static function time()
    {
        return static::request()->time();
    }


    /**
     * Get request instance
     * @return Http\Request
     */
    public static function request(Http\Request $request = null)
    {
        if($request) {
            static::$request = $request;
        }
        if(!static::$request) {
            static::$request = Http\Request::create();
        }

        return static::$request;
    }

}