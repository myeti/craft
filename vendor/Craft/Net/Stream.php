<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Net;

class Stream implements Request
{

    /** @var string */
    protected $url;

    /** @var array */
    protected $opts = [
        'secure'            => false,
        'method'            => 'get',
        'request_fulluri'   => false,
        'follow_location'   => 1,
        'max_redirects'     => 20,
        'protocol_version'  => 1.0,
        'ignore_errors'     => false
    ];


    /**
     * Setup request
     * @param string $url
     * @param array $data
     * @param string $method
     * @throws Error\BadRequestMethod
     */
    public function __construct($url, array $data = [], $method = Request::GET)
    {
        // format data
        $query = http_build_query($data);

        // get request
        if($method == Request::GET) {
            $this->url = $url . '?' . $query;
            $this->opt('method', 'get');
        }
        // post request
        elseif($method == Request::POST) {
            $this->opt('method', 'post');
            $this->opt('content', $query);
        }
        // error
        else {
            throw new Error\BadRequestMethod('Unknown "' . $method . '" method.');
        }

        $this->opt('method', strtolower($method));
    }


    /**
     * Set option
     * @param string $opt
     * @param mixed $value
     * @return $this
     */
    public function opt($opt, $value)
    {
        $this->opts[$opt] = $value;
        return $this;
    }


    /**
     * Send request
     * @return mixed
     */
    public function send()
    {
        $context = stream_context_create($this->opts);
        return file_get_contents($this->url, null, $context);
    }

} 