<?php

namespace craft\kit\remote;

use craft\kit\remote\context\HttpContext;

abstract class Request
{

    /**
     * Send request
     * @param $url
     * @param StreamContext $context
     * @return mixed
     */
    public static function make($url, StreamContext $context)
    {
        // make opts
        $opts = $context->opts();

        // create stream context
        $context = stream_context_create($opts);

        // execute query
        return file_get_contents($url, null, $context);
    }


    /**
     * Make get request
     * @param $url
     * @param array $params
     * @return mixed
     */
    public static function get($url, array $params = [])
    {
        // create http get context
        $context = new HttpContext();
        $context->method = 'get';
        $context->data = $params;

        return static::make($url, $context);
    }


    /**
     * Make post request
     * @param $url
     * @param array $data
     * @return mixed
     */
    public static function post($url, array $data = [])
    {
        // create http get context
        $context = new HttpContext();
        $context->method = 'post';
        $context->data = $data;

        return static::make($url, $context);
    }

} 