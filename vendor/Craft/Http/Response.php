<?php

namespace Craft\Http;

class Response implements ResponseInterface
{

    /** @var int */
    protected $code = 200;

    /** @var string */
    protected $format = 'text/html';

    /** @var string */
    protected $charset = 'utf-8';

    /** @var array */
    protected $headers = [];

    /** @var string */
    protected $content;

    /** @var array */
    protected $data = [];


    /**
     * New Response
     * @param null $content
     * @param int $code
     * @param array $headers
     */
    public function __construct($content = null, $code = 200, array $headers = [])
    {
        $this->content($content);
        $this->code($code);
        foreach($headers as $header => $value) {
            $this->header($header, $value);
        }
    }


    /**
     * Get or Set code
     * @param null|int $code
     * @return int
     */
    public function code($code = null)
    {
        if($code) {
            $this->code = $code;
        }

        return $this->code;
    }


    /**
     * Get or Set format
     * @param null|string $format
     * @return string
     */
    public function format($format = null)
    {
        if($format) {
            $this->format = $format;
        }

        return $this->format;
    }


    /**
     * Get or Set charset
     * @param null|string $charset
     * @return string
     */
    public function charset($charset = null)
    {
        if($charset) {
            $this->charset = $charset;
        }

        return $this->charset;
    }


    /**
     * Set header
     * @param string $name
     * @param string $value
     * @param bool $replace
     * @return $this
     */
    public function header($name, $value, $replace = true)
    {
        $name = strtolower($name);

        if(isset($this->headers[$name]) and !$replace) {
            if(is_array($this->headers[$name])) {
                array_push($this->headers[$name], $value);
            }
            else {
                $this->headers[$name] = [$this->headers[$name], $value];
            }
        }
        else {
            $this->headers[$name] = $value;
        }

        return $this;
    }


    /**
     * Set cookie
     * @param string $name
     * @param string $value
     * @param int $expires
     * @return mixed
     */
    public function cookie($name, $value, $expires = 0)
    {
        $cookie = urlencode($name);

        // has value
        if($value) {
            $cookie .= '=' . urlencode($value) . ';';
            $cookie .= ' expires=' . gmdate("D, d-M-Y H:i:s T", time() - 31536001) . ';';
        }
        // delete cookie
        else {
            $cookie .= '=deleted;';
            if($expires) {
                $cookie .= ' expires=' . gmdate("D, d-M-Y H:i:s T", time() - $expires);
            }
        }

        return $this->header('Set-Cookie', $cookie, false);
    }


    /**
     * Get/Set body
     * @param string $content
     * @return string
     */
    public function content($content = null)
    {
        if($content) {
            $this->content = $content;
        }

        return $this->content;
    }


    /**
     * Response already sent ?
     * @return bool
     */
    public function sent()
    {
        return headers_sent();
    }


    /**
     * Send response
     * @return string
     */
    public function send()
    {
        // send headers
        if(!$this->sent()) {

            // set content type
            if(!isset($this->headers['Content-Type'])) {
                $header = 'Content-Type: ' . $this->format;
                if($this->charset) {
                    $header .= '; charset=' . $this->charset;
                }
                header($header);
            }

            // set http code
            if(!Code::has($this->code)) {
                $this->code = 200;
            }
            header('HTTP/1.1 ' . $this->code . ' ' . Code::label($this->code), true, $this->code);

            // compile header
            foreach($this->headers as $name => $value) {
                if(is_array($value)) {
                    foreach($value as $subvalue) {
                        header($name . ': ' . $subvalue);
                    }
                }
                else {
                    header($name . ': ' . $value);
                }
            }
        }

        // send content
        echo (string)$this->content;

        return $this->sent();
    }
}