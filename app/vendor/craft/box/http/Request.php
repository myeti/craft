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

class Request extends Http
{

    /**
     * Init request
     * @param string $url
     * @param string $method
     * @param array $data
     */
    public function __construct($url, $method = Http::GET, array $data = [])
    {
        $this->method = $method;
        $this->url = $url;
        $this->data = $data;
    }

    /**
     * Send request
     * @return Response
     */
    public function send()
    {
        // create opts
        $opts = [
            'http' => [
                'method' => $this->method,
                'header' => implode("\r\n", $this->headers),
                'content' => http_build_query($this->data)
            ]
        ];

        // create stream context
        $context = stream_context_create($opts);
    }

} 