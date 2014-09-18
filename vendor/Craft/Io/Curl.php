<?php

namespace Craft\Io;

class Curl implements Transport
{

    /** @var resource */
    protected $curl;


    /**
     * Setup curl
     * @param string $url
     */
    public function __construct($url)
    {
        $this->open($url);
        $this->opt(CURLOPT_RETURNTRANSFER, 1);
    }


    /**
     * Open resource
     * @param string $url
     * @return bool
     */
    public function open($url)
    {
        if($this->curl) {
            $this->close();
        }

        $this->curl = curl_init($url);
        $this->opt(CURLOPT_URL, $url);

        return (bool)$this->curl;
    }


    /**
     * Receive data from service
     * @return mixed
     */
    public function receive() {}


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
     * Send data
     * @param null $null
     * @return mixed
     */
    public function send($null = null)
    {
        return curl_exec($this->curl);
    }


    /**
     * Close resource
     * @return bool
     */
    public function close()
    {
        curl_close($this->curl);
        return true;
    }


    /**
     * Create curl GET request
     * @param string $url
     * @param array $data
     * @return self
     */
    public static function get($url, array $data = [])
    {
        $query = http_build_query($data);
        return new self($url . '?' . $query);
    }


    /**
     * Create curl POST request
     * @param string $url
     * @param array $data
     * @return self
     */
    public static function post($url, array $data = [])
    {
        $curl = new self($url);
        $curl->opt(CURLOPT_POST, 1);
        $curl->opt(CURLOPT_POSTFIELDS, $data);

        return $curl;
    }

}