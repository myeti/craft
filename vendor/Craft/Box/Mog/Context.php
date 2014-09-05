<?php

namespace Craft\Box\Mog;

class Context
{

    /** @var string */
    public $root;

    /** @var int */
    public $code;

    /** @var bool */
    public $https;

    /** @var bool */
    public $http;

    /** @var string */
    public $protocol;

    /** @var string */
    public $method;

    /** @var bool */
    public $async;

    /** @var bool */
    public $sync;

    /** @var string */
    public $browser;

    /** @var string */
    public $mobile;

    /** @var string */
    public $host;

    /** @var string */
    public $url;

    /** @var string */
    public $query;

    /** @var string */
    public $base;

    /** @var string */
    public $fullurl;

    /** @var string */
    public $from;

    /** @var string */
    public $ip;

    /** @var bool */
    public $local;

    /** @var string */
    public $time;

    /** @var string */
    public $timezone;

    /** @var string */
    public $locale;

    /** @var array */
    protected $dialog = [
        'Kupo ?!',
        'I\'m hungry...',
        'May I help you ?',
        'It\'s dark in here...',
        'I haven\'t received any mail lately, Kupo.',
        'It\'s dangerous outside ! Kupo !',
        'Don\'t call me if you don\'t need me, Kupo !',
        'What do you want to do, Kupo ?'
    ];


    /**
     * Generate request from env
     */
    public function __construct()
    {
        // physical path
        $this->root = dirname($this->server('SCRIPT_FILENAME'));

        // http
        $this->code = http_response_code();
        $this->https = ($this->server('HTTP', 'off') == 'on');
        $this->http = !$this->https;
        $this->protocol = $this->https ? 'https' : 'http';
        $this->method = $this->server('REQUEST_METHOD', 'GET');
        $this->async = $this->server('HTTP_X_REQUESTED_WITH', false)
                       && strtolower($this->server('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest';
        $this->sync = !$this->async;

        // devices
        $this->browser = @get_browser()->browser;
        $this->mobile = $this->server('HTTP_X_WAP_PROFILE', false) or $this->server('HTTP_PROFILE', false);

        // url
        $this->host = $this->server('HTTP_HOST');
        $this->url = $this->server('REQUEST_URI');
        $this->query = $this->server('PATH_INFO', '/');
        $this->base = substr($this->url, 0, -strlen($this->query));
        $this->fullurl = $this->protocol . '://' . $this->host . $this->url;
        $this->from = $this->server('HTTP_REFERER');

        // user
        $this->ip = $this->server('REMOTE_ADDR');
        $this->local = in_array($this->ip, ['127.0.0.1', '::1']);
        $this->time = $this->server('REQUEST_TIME_FLOAT');
        $this->timezone = date_default_timezone_get();
        $this->locale = locale_get_default();
    }


    /**
     * Get physical path
     * @return string
     */
    public function path()
    {
        return $this->root . implode(DIRECTORY_SEPARATOR, func_get_args());
    }


    /**
     * Get physical path
     * @return string
     */
    public function url()
    {
        return $this->base . implode('/', func_get_args());
    }


    /**
     * $_GET value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $fallback;
    }


    /**
     * $_GET values
     * @return array
     */
    public function gets()
    {
        return $_GET;
    }


    /**
     * $_POST value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public function post($key, $fallback = null)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $fallback;
    }


    /**
     * $_POST values
     * @return array
     */
    public function posts()
    {
        return $_POST;
    }


    /**
     * $_FILES value
     * @param  string $key
     * @param  string $fallback
     * @return array|object
     */
    public function file($key, $fallback = null)
    {
        return isset($_FILES[$key]) ? (object)$_FILES[$key] : $fallback;
    }


    /**
     * $_FILES values
     * @return array
     */
    public function files()
    {
        return $_FILES;
    }


    /**
     * $_SERVER value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public function server($key, $fallback = null)
    {
        return isset($_SERVER[$key]) ? $_SERVER[$key] : $fallback;
    }


    /**
     * $_SERVER values
     * @return array
     */
    public function servers()
    {
        return $_SERVER;
    }


    /**
     * Headers value
     * @param string $key
     * @param null $fallback
     * @return mixed
     */
    public function header($key, $fallback = null)
    {
        $headers = $this->headers();
        return isset($headers[$key]) ? $headers[$key] : $fallback;
    }


    /**
     * Header values
     * @return array
     */
    public function headers()
    {
        return function_exists('getallheaders') ? getallheaders() : [];
    }


    /**
     * Get env value
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    public function env($key, $fallback = null)
    {
        return isset($_ENV[$key]) ? $_ENV[$key] : $fallback;
    }


    /**
     * $_GET values
     * @return array
     */
    public function envs()
    {
        return $_ENV;
    }


    /**
     * Set env mode (dev, test, prod)
     * @param string $mode
     * @return mixed
     */
    public function set($mode)
    {
        $_ENV['mode'] = $mode;
    }


    /**
     * Check env mode
     * @param string $mode
     * @return bool
     */
    public function in($mode)
    {
        return ($_ENV['mode'] == $mode);
    }


    /**
     * Get or set timezone
     * @param string $timezone
     */
    public function timezone($timezone)
    {
        date_default_timezone_set($timezone);
        $this->timezone = $timezone;
    }


    /**
     * Get or set locale
     * @param $lang
     */
    public function locale($lang)
    {
        setlocale(LC_ALL, $lang);
        locale_set_default($lang);
    }


    /**
     * Kupo !
     * @return string
     */
    public function kupo()
    {
        return 'o-&#949;(:o) ' . $this->dialog[array_rand($this->dialog)];
    }
    
} 