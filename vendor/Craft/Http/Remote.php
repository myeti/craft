<?php

namespace Craft\Http;

use Craft\Io\Curl;

class Remote
{

    const RAW = 0;
    const JSON = 1;

    /** @var string */
    protected $uri;

    /** @var string */
    protected $query;

    /** @var int */
    protected $format = self::JSON;

    /** @var array */
    protected $headers = [];


    /**
     * New remote query
     * @param string $uri
     * @param int $format
     * @param array $headers
     */
    public function __construct($uri, $format = self::RAW, array $headers = [])
    {
        $this->uri = rtrim($uri, '/');
        $this->query = $this->uri;
        $this->format = $format;
        $this->headers = $headers;
    }


    /**
     * Build uri segments
     * @param string $segment
     * @param array $values
     * @return $this
     */
    public function __call($segment, array $values = [])
    {
        $this->query .= '/' . trim($segment, '/');
        $this->query .= '/' . trim(implode('/', $values), '/');

        return $this;
    }


    /**
     * Do OPTIONS query
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function options(array $data = [], array $headers = [])
    {
        return $this->execute(Method::OPTIONS, $data, $headers);
    }


    /**
     * Do GET query
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function get(array $data = [], array $headers = [])
    {
        return $this->execute(Method::GET, $data, $headers);
    }


    /**
     * Do HEAD query
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function head(array $data = [], array $headers = [])
    {
        return $this->execute(Method::HEAD, $data, $headers);
    }


    /**
     * Do POST query
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function post(array $data = [], array $headers = [])
    {
        return $this->execute(Method::POST, $data, $headers);
    }


    /**
     * Do PUT query
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function put(array $data = [], array $headers = [])
    {
        return $this->execute(Method::PUT, $data, $headers);
    }


    /**
     * Do DELETE query
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function delete(array $data = [], array $headers = [])
    {
        return $this->execute(Method::DELETE, $data, $headers);
    }


    /**
     * Execute custom method request
     * @param string $method
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    protected function execute($method, array $data = [], array $headers = [])
    {
        // uri params
        if($method < Method::POST and $query = http_build_query($data)) {
            $this->query .= '?' . $query;
        }

        // create curl connector
        $curl = new Curl($this->query);

        // get type
        if($method === Method::GET) {
            $curl->opt(CURLOPT_HTTPGET, true);
        }
        // post type
        elseif($method === Method::POST) {
            $curl->opt(CURLOPT_POST, true);
        }
        // put type
        elseif($method === Method::PUT) {
            $curl->opt(CURLOPT_PUT, true);
        }
        // custom type
        else {
            $curl->opt(CURLOPT_CUSTOMREQUEST, strtoupper($method));
        }

        // head no-content
        if($method === Method::HEAD) {
            $curl->opt(CURLOPT_NOBODY, true);
        }
        // post-able data
        elseif($method >= Method::POST) {
            $curl->opt(CURLOPT_POSTFIELDS, $data);
        }

        // set headers
        if($headers = array_merge($this->headers, $headers)) {
            $curl->opt(CURLOPT_HTTPHEADER, $headers);
        }

        // do request
        $content = $curl->send();

        // reset query
        $this->query = $this->uri;

        // format to json
        if($this->format === self::JSON) {
            $content = json_decode($content);
        }

        // format content
        return $content;
    }

} 