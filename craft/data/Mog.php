<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\data;

abstract class Mog
{

    /** @var array */
    protected static $_get = [];

    /** @var array */
    protected static $_post = [];

    /** @var array */
    protected static $_files = [];

    /** @var array */
    protected static $_server = [];

    /** @var array */
    protected static $_headers = [];

    /** @var string */
    protected static $_ip = '127.0.0.1';

    /** @var bool */
    protected static $_local = false;

    /** @var string */
    protected static $_method = 'get';

    /** @var bool */
    protected static $_sync = true;

    /** @var bool */
    protected static $_async = false;

    /** @var bool */
    protected static $_mobile = false;

    /** @var string */
    protected static $_browser = 'unknown';

    /** @var float */
    protected static $_time = 0;


    /**
     * Create request from globals
     */
    public static function init()
    {
        // request data
        static::$_get = &$_GET;
        static::$_post = &$_POST;
        static::$_files = &$_FILES;
        static::$_server = &$_SERVER;
        static::$_headers = getallheaders();

        // advanced data
        static::$_ip = static::$_server['REMOTE_ADDR'];
        static::$_local = in_array(static::$_ip, ['127.0.0.1', '::1']);
        static::$_method = static::$_server['REQUEST_METHOD'];
        static::$_async = isset(static::$_server['HTTP_X_REQUESTED_WITH']) and strtolower(static::$_server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        static::$_sync = !static::$_async;
        static::$_mobile = isset(static::$_server['HTTP_X_WAP_PROFILE']) or isset(static::$_server['HTTP_PROFILE']);

        // find browser
        foreach(['Firefox', 'Safari', 'Chrome', 'Opera', 'MSIE'] as $browser)
            if(strpos(static::$_server['HTTP_USER_AGENT'], $browser))
                static::$_browser = $browser;

        // stopwatch
        static::$_time = microtime(true);
    }


    /**
     * $_GET value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public static function get($key = null, $fallback = null)
    {
        // fallback
        if($key and !isset(static::$_get[$key])) {
            return $fallback;
        }

        return $key ? static::$_get[$key] : static::$_get;
    }


    /**
     * $_POST value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public static function post($key = null, $fallback = null)
    {
        // fallback
        if($key and !isset(static::$_post[$key])) {
            return $fallback;
        }

        return $key ? static::$_post[$key] : static::$_post;
    }


    /**
     * $_FILES value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public static function file($key = null, $fallback = null)
    {
        // fallback
        if($key and !isset(static::$_files[$key])) {
            return $fallback;
        }

        return $key ? static::$_files[$key] : static::$_files;
    }


    /**
     * $_SERVER value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public static function server($key = null, $fallback = null)
    {
        // fallback
        if($key and !isset(static::$_server[$key])) {
            return $fallback;
        }

        return $key ? static::$_server[$key] : static::$_server;
    }


    /**
     * headers value
     * @param  string $key
     * @return mixed
     */
    public static function header($key = null)
    {
        return $key ? static::$_headers[$key] : static::$_headers;
    }


    /**
     * Get IP
     * @return string
     */
    public static function ip()
    {
        return static::$_ip;
    }


    /**
     * Is local
     * @return bool
     */
    public static function local()
    {
        return static::$_local;
    }


    /**
     * Get method
     * @return string
     */
    public static function method()
    {
        return static::$_method;
    }


    /**
     * Is synchronous
     * @return bool
     */
    public static function sync()
    {
        return static::$_sync;
    }


    /**
     * Get asynchronous
     * @return bool
     */
    public static function async()
    {
        return static::$_async;
    }


    /**
     * Get browser name
     * @return string
     */
    public static function browser()
    {
        return static::$_browser;
    }


    /**
     * Is mobile
     * @return bool
     */
    public static function mobile()
    {
        return static::$_mobile;
    }


    /**
     * Get elapsed time
     * @return float
     */
    public static function elasped()
    {
        $now = microtime(true);
        return number_format($now - static::$_time, 4);
    }


    /**
     * Kupo !
     * @return string
     */
    public static function kupo()
    {
        $dialog = [
            'Kupo ?!',
            'I\'m hungry...',
            'May I help you ?',
            'It\'s dark in here...',
            'I haven\'t received any mail lately, Kupo.',
            'It\'s dangerous outside ! Kupo !',
            'Don\'t call me if you don\'t need me, Kupo !',
            'What do you want to do, Kupo ?'
        ];

        return 'o-&#949;(:o) ' . $dialog[array_rand($dialog)];
    }

}