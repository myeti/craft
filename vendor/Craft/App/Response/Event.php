<?php

namespace Craft\App\Response;

use Craft\App\Request;
use Craft\App\Response;

abstract class Event
{

    /**
     * Generate 200 response
     * @param string $content
     * @return Response
     */
    public static function ok($content = null)
    {
        return static::embed(Response::ok($content));
    }


    /**
     * Generate 404 response
     * @param string $content
     * @return Response
     */
    public static function notFound($content = null)
    {
        return static::embed(Response::notFound($content));
    }


    /**
     * Generate 403 response
     * @param string $content
     * @return Response
     */
    public static function forbidden($content = null)
    {
        return static::embed(Response::forbidden($content));
    }


    /**
     * Generate json response
     * @param array $data
     * @return Response
     */
    public static function json(array $data)
    {
        return static::embed(Response::json($data));
    }


    /**
     * Generate template response
     * @param string $template
     * @param array $data
     * @return Response
     */
    public static function view($template, array $data = [])
    {
        return static::embed(Response::view($template, $data));
    }


    /**
     * Generate downloadable file response
     * @param string $filename
     * @return Response
     */
    public static function download($filename)
    {
        return static::embed(Response::download($filename));
    }


    /**
     * Generate redirect response
     * @param string $url
     * @param bool $outside
     * @return Response
     */
    public static function redirect($url, $outside = false)
    {
        return static::embed(Response::redirect($url, $outside));
    }


    /**
     * Embed response in event callback
     * @param Response $r
     * @return callable
     */
    protected static function embed(Response $r)
    {
        return function(Request $request, Response &$response) use($r) {
            $response = $r;
        };
    }

} 