<?php

namespace Craft\Net;

class Curl implements Request
{

    /** @var resource */
    protected $curl;


    /**
     * Setup request
     * @param string $url
     * @param array $data
     * @param string $method
     * @throws Error\BadRequestMethod
     * @internal param string $method
     */
    public function __construct($url, array $data = [], $method = Request::GET)
    {
        // format data
        $query = http_build_query($data);

        // get request
        if($method == Request::GET) {
            $this->curl = curl_init($url . '?' . $query);
        }
        // post request
        elseif($method == Request::POST) {
            curl_setopt($this->curl, CURLOPT_POST, 1);
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $query);
        }
        // error
        else {
            throw new Error\BadRequestMethod('Unknown "' . $method . '" method.');
        }

        // default opts
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
    }


    /**
     * Set option
     * @param int $opt
     * @param mixed $value
     * @return $this
     */
    public function opt($opt, $value)
    {
        curl_setopt($this->curl, $opt, $value);
        return $this;
    }


    /**
     * Send request
     * @return mixed
     */
    public function send()
    {
        $data = curl_exec($this->curl);
        curl_close($this->curl);
        return $data;
    }

} 