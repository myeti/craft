<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App;
use Craft\Debug\Error\FileNotFound;
use Craft\View\Engine;

/**
 * The Response object contains
 * the content and the data that
 * will be send to the browser.
 */
class Response
{

    /** @var int */
    public $code = 200;

    /** @var string */
    public $format = 'text/html';

    /** @var array */
    public $headers = [];

    /** @var array */
    public $data = [];

    /** @var string */
    public $content = '';

    /** @var string */
    public $halt = false;

    /** @var array */
    protected static $status = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            // RFC2518
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          // RFC4918
        208 => 'Already Reported',      // RFC5842
        226 => 'IM Used',               // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    // RFC-reschke-http-status-308-07
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                               // RFC2324
        422 => 'Unprocessable Entity',                                        // RFC4918
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates (Experimental)',                      // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
    ];


    /**
     * New content response
     * @param string $content
     * @param int $code
     * @param array $headers
     */
    public function __construct($content = '', $code = 200, array $headers = [])
    {
        $this->content = $content;
        $this->code = $code;
        $this->headers = $headers;
    }


    /**
     * Set header
     * @param $name
     * @param $value
     * @return $this
     */
    public function header($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }


    /**
     * Set format
     * @param $format
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;
        return $this;
    }


    /**
     * Set code
     * @param $code
     * @return $this
     */
    public function code($code)
    {
        $this->code = $code;
        return $this;
    }


    /**
     * Set data
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;
        return $this;
    }


    /**
     * Send response
     * @return bool
     */
    public function send()
    {
        // header already sent ?
        $sent = headers_sent();
        if(!$sent) {

            // set content type
            if(!isset($this->headers['Content-Type'])) {
                header('Content-Type: ' . $this->format . '; charset=UTF-8');
            }

            // set http code
            if(!static::status($this->code)) {
                $this->code = 418;
            }
            header('HTTP/1.1 ' . $this->code . ' ' . static::status($this->code), true, $this->code);

            // compile header
            foreach($this->headers as $name => $value) {
                header($name . ': ' . $value);
            }

        }

        // send content
        echo (string)$this->content;
        return $sent;
    }


    /**
     * Callable response
     */
    public function __invoke()
    {
        return $this->send();
    }


    /**
     * Get status code
     * @param int $code
     * @return string|bool
     */
    public static function status($code)
    {
        return isset(static::$status[$code])
            ? static::$status[$code]
            : false;
    }


    /**
     * Generate 200 response
     * @param string $content
     * @return Response
     */
    public static function ok($content = null)
    {
        return new self($content);
    }


    /**
     * Generate 404 response
     * @param string $content
     * @return Response
     */
    public static function notFound($content = null)
    {
        return new self($content, 404);
    }


    /**
     * Generate 403 response
     * @param string $content
     * @return Response
     */
    public static function forbidden($content = null)
    {
        return new self($content, 403);
    }


    /**
     * Generate json response
     * @param array $data
     * @return Response
     */
    public static function json(array $data)
    {
        $response = new self(json_encode($data, JSON_PRETTY_PRINT));
        $response->format('application/json');

        return $response;
    }


    /**
     * Generate template response
     * @param string $template
     * @param array $data
     * @return Response
     */
    public static function view($template, array $data = [])
    {
        return new self(Engine::make($template, $data));
    }


    /**
     * Generate downloadable file response
     * @param string $filename
     * @throws \Craft\Debug\Error\FileNotFound
     * @return Response
     */
    public static function download($filename)
    {
        // no file
        if(!file_exists($filename)) {
            throw new FileNotFound('File "' . $filename . '" not found.');
        }

        $response = new self(file_get_contents($filename));
        $response->format('application/octet-stream');
        $response->header('Content-Transfer-Encoding', 'Binary');
        $response->header('Content-disposition', 'attachment; filename="' . basename($filename) . '"');

        return $response;
    }


    /**
     * Generate redirect response
     * @param string $url
     * @param bool $outside
     * @return Response
     */
    public static function redirect($url, $outside = false)
    {
        $response = new self;
        $response->code = 302;
        $response->header('Location', $outside ? $url : url($url));
        $response->halt = true;

        return $response;
    }

} 