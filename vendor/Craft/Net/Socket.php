<?php

namespace Craft\Net;

class Socket implements Request
{

    /** @var resource */
    protected $socket;

    /** @var string */
    protected $header;

    /** @var array */
    protected $opts = [];

    /** @var string */
    protected $content;


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

        // parse url
        $parts = parse_url($url) + ['port' => 80];

        // get request
        if($method == Request::GET) {
            $parts['path'] .= '?' . $query;
        }
        // post request
        elseif($method == Request::POST) {
            $this->content = $query;
        }
        // error
        else {
            throw new Error\BadRequestMethod('Unknown "' . $method . '" method.');
        }

        // create header
        $this->header = $method . ' ' . $parts['path'] . 'HTTP/1.1' . "\r\n";

        // default opts
        $this->opt('Host', $parts['host']);
        $this->opt('Content-Type', 'application/x-www-form-urlencoded');
        $this->opt('Content-Length', strlen($this->content));
        $this->opt('Connection', 'Close');

        // open socket
        $this->socket = fsockopen($parts['host'], $parts['port'], $errno, $errstr, 30);
    }


    /**
     * Set option
     * @param string $opt
     * @param mixed $value
     * @return $this
     */
    public function opt($opt, $value)
    {
        $this->opts[$opt] = $opt . ':' . $value;
        return $this;
    }


    /**
     * Send request
     * @return mixed
     */
    public function send()
    {
        // generate content
        $content = $this->header . implode("\r\n", $this->opts);
        if($this->content) {
            $content .= $this->content;
        }

        fwrite($this->socket, $content);
        fclose($this->socket); // async

        return true;
    }

}