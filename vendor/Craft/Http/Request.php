<?php

namespace Craft\Http;

class Request implements RequestInterface
{

    /** @var Url */
    protected $url;

    /** @var Request\Accept */
    protected $accept;

    /** @var Request\Cli */
    protected $cli;

    /** @var array */
    protected $data = [];


    /**
     * New Request
     * @param Url $url
     * @param Request\Accept $accept
     * @param Request\Cli $cli
     */
    public function __construct(Url $url = null, Request\Accept $accept = null, Request\Cli $cli = null)
    {
        $this->url = $url ?: Url::current();
        $this->accept = $accept ?: Request\Accept::create();
        $this->cli = $cli ?: Request\Cli::create();

        // default values from cli mode
        if(php_sapi_name() == 'cli') {
            date_default_timezone_set('UTC');
        }
    }


    /**
     * Get code
     * @return int
     */
    public function code()
    {
        return http_response_code();
    }


    /**
     * Get method
     * @return string
     */
    public function method()
    {
        $this->server('REQUEST_METHOD');
    }


    /**
     * Is secure
     * @return bool
     */
    public function secure()
    {
        return ($this->server('HTTPS') == 'on');
    }


    /**
     * Is async
     * @return bool
     */
    public function ajax()
    {
        return $this->server('HTTP_X_REQUESTED_WITH')
            && strtolower($this->server('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest';
    }


    /**
     * Get/Set url
     * @param Url $url
     * @return Url
     */
    public function url(Url $url = null)
    {
        if($url) {
            $this->url = $url;
        }

        return $this->url;
    }


    /**
     * Get path from root
     * @param string $path
     * @return string
     */
    public function path(...$path)
    {
        $root = dirname($this->server('SCRIPT_FILENAME'));
        $root = rtrim(DIRECTORY_SEPARATOR, $root) . DIRECTORY_SEPARATOR;

        $path = implode(DIRECTORY_SEPARATOR, $path);
        $path = DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR);

        return $root . $path;
    }


    /**
     * Get header
     * @return string
     */
    public function header($name)
    {
        $headers = [];
        if(function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        }

        return isset($headers[$name]) ? $headers[$name] : null;
    }


    /**
     * Get headers
     * @return array
     */
    public function headers()
    {
        $headers = [];
        if(function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
        }

        return $headers;
    }


    /**
     * Get _server
     * @return string
     */
    public function server($name)
    {
        return isset($_SERVER[$name]) ? $_SERVER[$name] : null;
    }


    /**
     * Get all _server
     * @return array
     */
    public function servers()
    {
        return $_SERVER;
    }


    /**
     * Get _env
     * @return string
     */
    public function env($name)
    {
        return isset($_ENV[$name]) ? $_ENV[$name] : null;
    }


    /**
     * Get all _env
     * @return array
     */
    public function envs()
    {
        return $_ENV;
    }


    /**
     * Get _get
     * @return string
     */
    public function param($name)
    {
        return isset($_GET[$name]) ? $_GET[$name] : null;
    }


    /**
     * Get all _get
     * @return array
     */
    public function params()
    {
        return $_GET;
    }


    /**
     * Get _post
     * @return string
     */
    public function value($name)
    {
        return isset($_POST[$name]) ? $_POST[$name] : null;
    }


    /**
     * Get all _post
     * @return array
     */
    public function values()
    {
        return $_POST;
    }


    /**
     * Get _file
     * @return Request\File
     */
    public function file($name)
    {
        return isset($_FILES[$name])
            ? new Request\File($_FILES[$name])
            : null;
    }


    /**
     * Get all _file
     * @return Request\File[]
     */
    public function files()
    {
        $files = [];
        foreach($_FILES as $name => $file) {
            $files[] = new Request\File($_FILES[$name]);
        }

        return $files;
    }


    /**
     * Get _cookie
     * @return string
     */
    public function cookie($name)
    {
        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }


    /**
     * Get all _cookie
     * @return array
     */
    public function cookies()
    {
        return $_COOKIE;
    }


    /**
     * Get all argv
     * @param Request\Cli $cli
     * @return Request\Cli
     */
    public function cli(Request\Cli $cli = null)
    {
        if($cli) {
            $this->cli = $cli;
        }

        return $this->cli;
    }


    /**
     * Get accept header
     * @param Request\Accept $accept
     * @return Request\Accept
     */
    public function accept(Request\Accept $accept = null)
    {
        if($accept) {
            $this->accept = $accept;
        }

        return $this->accept;
    }


    /**
     * Get user agent
     * @return string
     */
    public function agent()
    {
        return $this->server('HTTP_USER_AGENT');
    }


    /**
     * Get user ip
     * @return string
     */
    public function ip()
    {
        return $this->server('REMOTE_ADDR');
    }


    /**
     * Get user locale
     * @return string
     */
    public function locale()
    {
        $locale = $this->server('HTTP_ACCEPT_LANGUAGE');
        return locale_accept_from_http($locale);
    }


    /**
     * Get time
     * @return float
     */
    public function time()
    {
        return $this->server('REQUEST_TIME');
    }


    /**
     * Set custom data
     * @return $this
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }


    /**
     * Get custom data
     * @return mixed
     */
    public function get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }


    /**
     * Generate from env
     * @param int $strategy
     * @return static
     */
    public static function create($strategy = Url::PATH_INFO)
    {
        $url = Url::current($strategy);
        return new static($url);
    }

}