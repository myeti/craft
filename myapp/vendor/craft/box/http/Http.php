<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box\core\kit\http;

abstract class Http
{

    /** methods */
    const GET = 'GET';
    const POST = 'POST';
    const HEAD = 'HEAD';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    /** @var string */
    public $wrapper = 'http';

    /** @var string */
    public $url;

    /** @var array */
    public $headers = [];

    /** @var string */
    public $method = self::GET;

    /** @var array */
    public $data = [];


    /**
     * Add header
     * @param string $header
     * @param string $value
     * @return $this
     */
    public function header($header, $value)
    {
        $this->headers[] = $header . ': ' . $value;
        return $this;
    }


    /**
     * Add many headers
     * @param array $headers
     * @return $this
     */
    public function headers(array $headers)
    {
        foreach($headers as $header => $value) {
            $this->header($header, $value);
        }

        return $this;
    }


    /**
     * Add param
     * @param $key
     * @param $value
     * @return $this
     */
    public function data($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }


    /**
     * Send Http object (request or response)
     * @return mixed
     */
    abstract public function send();

} 