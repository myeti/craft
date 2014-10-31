<?php

namespace Craft\Box;

class Context
{

    /** @var string */
    public $root;

    /** @var Context\Http */
    public $http;

    /** @var array */
    public $args = [];

    /** @var array */
    public $form = [];

    /** @var array */
    public $files = [];

    /** @var array */
    public $server = [];

    /** @var array */
    public $env = [];

    /** @var array */
    public $header = [];

    /** @var string */
    public $browser;

    /** @var string */
    public $mobile;

    /** @var Context\Url */
    public $url;

    /** @var string */
    public $sapi;

    /** @var string */
    public $ip;

    /** @var bool */
    public $local;

    /** @var string */
    public $mode;

    /** @var string */
    public $time;

    /** @var string */
    public $timezone;

    /** @var string */
    public $locale;

    /** @var array */
    protected $kupo = [
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
     * Default init
     */
    public function __construct()
    {
        $this->http = new Context\Http;
        $this->url = new Context\Url;
    }


    /**
     * Get physical path
     * @return string
     */
    public function path(...$args)
    {
        return $this->root . implode(DIRECTORY_SEPARATOR, $args);
    }


    /**
     * Get physical path
     * @return string
     */
    public function url(...$args)
    {
        return $this->url->base . implode('/', $args);
    }


    /**
     * $_GET value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public function arg($key, $fallback = null)
    {
        return isset($this->args[$key]) ? $this->args[$key] : $fallback;
    }


    /**
     * $_POST value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public function form($key, $fallback = null)
    {
        return isset($this->form[$key]) ? $this->form[$key] : $fallback;
    }


    /**
     * $_FILES value
     * @param  string $key
     * @param  string $fallback
     * @return array|object
     */
    public function file($key, $fallback = null)
    {
        return isset($this->files[$key]) ? $this->files[$key] : $fallback;
    }


    /**
     * $_SERVER value
     * @param  string $key
     * @param  string $fallback
     * @return mixed
     */
    public function server($key, $fallback = null)
    {
        return isset($this->server[$key]) ? $this->server[$key] : $fallback;
    }


    /**
     * Headers value
     * @param string $key
     * @param null $fallback
     * @return mixed
     */
    public function header($key, $fallback = null)
    {
        return isset($this->header[$key]) ? $this->header[$key] : $fallback;
    }


    /**
     * Get env value
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    public function env($key, $fallback = null)
    {
        return isset($this->env[$key]) ? $this->env[$key] : $fallback;
    }


    /**
     * Set env mode (dev, test, prod)
     * @param string $mode
     * @return mixed
     */
    public function mode($mode)
    {
        $this->env['ENV_MODE'] = $mode;
    }


    /**
     * Check env mode
     * @param string $mode
     * @return bool
     */
    public function in($mode)
    {
        return ($this->env['ENV_MODE'] == $mode);
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
        return 'o-&#949;(:o) ' . array_random($this->kupo);
    }


    /**
     * Generate request from web env
     * @return Context
     */
    public static function web()
    {
        // create request
        $request = new static;

        // physical path
        $request->root = dirname($request->server('SCRIPT_FILENAME'));

        // http
        $request->http->code = http_response_code();
        $request->http->secure = ($request->server('HTTP', 'off') == 'on');
        $request->http->protocol = $request->http->secure ? 'https' : 'http';
        $request->http->method = $request->server('REQUEST_METHOD', 'GET');
        $request->http->ajax = $request->server('HTTP_X_REQUESTED_WITH', false)
            && strtolower($request->server('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest';

        // data
        $request->form = &$_POST;
        $request->args = &$_GET;
        $request->server = &$_SERVER;
        $request->env = &$_ENV;
        $request->header = function_exists('getallheaders') ? getallheaders() : [];
        foreach($_FILES as $file) {
            $request->files[] = new Context\FormFile($file);
        }

        // sapi
        $request->sapi = php_sapi_name();

        // devices
        $request->browser = @get_browser()->browser;
        $request->mobile = $request->server('HTTP_X_WAP_PROFILE', false) || $request->server('HTTP_PROFILE', false);

        // url
        $request->url->host = $request->server('HTTP_HOST');
        $request->url->full = $request->server('REQUEST_URI');
        $request->url->query = $request->server('PATH_INFO', '/');
        $request->url->base = substr($request->url, 0, -strlen($request->url->query));
        $request->url->from = $request->server('HTTP_REFERER');

        // user
        $request->ip = $request->server('REMOTE_ADDR');
        $request->local = in_array($request->ip, ['127.0.0.1', '::1']);
        $request->mode = $request->env('ENV_MODE');
        $request->time = $request->server('REQUEST_TIME_FLOAT');
        $request->timezone = @date_default_timezone_get();
        $request->locale = @locale_get_default();

        return $request;
    }


    /**
     * Generate request from cli env
     * @return Context
     */
    public static function cli($root)
    {
        // create request
        $request = new static;

        // data
        $request->server = &$_SERVER;
        $request->env = &$_ENV;

        // argv
        $argv = $request->server('argv', []);
        array_shift($argv);

        // physical path
        $request->root = $root;

        // sapi
        $request->sapi = php_sapi_name();
        $request->args = $argv;

        // user
        $request->local = true;
        $request->time = microtime(true);
        $request->timezone = @date_default_timezone_get();
        if(!$request->timezone or $request->timezone == 'UTC') {
            $request->timezone('Europe/Paris');
        }
        if(function_exists('locale_get_default')) {
            $request->locale = locale_get_default();
        }

        return $request;
    }
    
} 