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

use Craft\Http;
use Craft\Debug\Error;

/**
 * The Response object contains
 * the content and the data that
 * will be send to the browser.
 */
class Response extends Http\Response
{

    /**
     * Generate json response
     * @param array $data
     * @return Response
     */
    public static function json(array $data)
    {
        $response = new static(json_encode($data, JSON_PRETTY_PRINT));
        $response->format('application/json');

        return $response;
    }


    /**
     * Generate downloadable file response
     * @param string $filename
     * @throws Error\FileNotFound
     * @return Response
     */
    public static function download($filename)
    {
        // no file
        if(!file_exists($filename)) {
            throw new Error\FileNotFound('File "' . $filename . '" not found.');
        }

        $response = new static(file_get_contents($filename));
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
    public static function redirect($url, $code = 302, $outside = false)
    {
        $url = $outside ? $url : url($url);

        $response = new static(null, $code);
        $response->header('Location', $url);

        return $response;
    }

} 