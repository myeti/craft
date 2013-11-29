<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box;

abstract class Rest
{

    /**
     * Make GET request
     * @param string $url
     * @param array $data
     * @return string
     */
    public static function get($url, array $data = [])
    {
        // build
    	if(!empty($data)) {
    		$url .= '?' . http_build_query($data);
    	}

        return self::query($path, 'GET');
    }


    /**
     * Make POST request
     * @param string $url
     * @param array $data
     * @return string
     */
    public static function post($url, array $data = [])
    {
        $header = 'Content-type: application/x-www-form-urlencoded';
        return self::query($url, 'POST', $header, $data);
    }


    /**
     * Forge request
     * @param string $url
     * @param string $method
     * @param string $header
     * @param array $data
     * @return string
     */
    public static function query($url, $method, $header = null, array $data = [])
    {
    	// build context
        $config = [
        	'http' => [
        		'method' => strtoupper($method),
        		'header' => $header,
        		'content' => $data
        	]
        ];
        $context = stream_context_create($config);

        // do query
        return file_get_contents($url, false, $context);
    }

}