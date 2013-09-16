<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 *
 * @author Aymeric Assier <aymeric.assier@gmail.com>
 * @date 2013-09-12
 * @version 0.1
 */
namespace craft;

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
    public $headers;

    /** @var string */
    public $ip;

    /** @var bool */
    public $local;

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


    /**
     * Easter Egg, Kupo !
     * @return string
     */
    public function __toString()
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