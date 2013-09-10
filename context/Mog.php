<?php

namespace craft\session;

class Mog extends \ArrayObject
{

    /** @var array */
    public $get = [];

    /** @var array */
    public $post = [];

    /** @var array */
    public $files = [];

    /** @var array */
    public $server;

    /** @var array */
    public $env;

    /** @var array */
    public $headers;

    /** @var string */
    public $ip;

    /** @var bool */
    public $local;

    /** @var string */
    public $lang = 'fr-FR';

    /** @var string */
    public $method = 'get';

    /** @var bool */
    public $sync = true;

    /** @var bool */
    public $async = false;

    /** @var bool */
    public $mobile = false;

    /** @var string */
    public $browser = 'unknown';

    /** @var float */
    public $start = 0;


    /**
     * Create request from globals
     */
    public function __construct()
    {
        // user data
        $this->get = &$_GET;
        $this->post = &$_POST;
        $this->files = &$_FILES;
        $this->server = &$_SERVER;
        $this->env = &$_ENV;

        // env data
        $this->headers = getallheaders();

        // advanced data
        $this->ip = $this->server['REMOTE_ADDR'];
        $this->local = in_array($this->ip, ['127.0.0.1', '::1']);
        $this->lang = explode(',', $this->server['HTTP_ACCEPT_LANGUAGE'])[0];
        $this->method = $this->server['REQUEST_METHOD'];
        $this->async = isset($this->server['HTTP_X_REQUESTED_WITH']) and strtolower($this->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $this->sync = !$this->async;
        $this->mobile = isset($this->server['HTTP_X_WAP_PROFILE']) or isset($this->server['HTTP_PROFILE']);

        // find browser
        foreach(['Firefox', 'Safari', 'Chrome', 'Opera', 'MSIE'] as $browser)
            if(strpos($this->server['HTTP_USER_AGENT'], $browser))
                $this->browser = $browser;

        // stopwatch
        $this->start = microtime(true);
    }

}