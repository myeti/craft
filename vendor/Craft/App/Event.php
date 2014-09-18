<?php

namespace Craft\App;

class Event
{

    /** @var Response */
    protected $response;


    /**
     * Embed response object
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }


    /**
     * Callable event
     * @param Request $request
     * @param Response $response
     */
    public function __invoke(Request $request, Response &$response)
    {
        $response = $this->response;
    }


    /**
     * Generate 200 response
     * @param string $content
     * @return Response
     */
    public static function ok($content = null)
    {
        return new self(Response::ok($content));
    }


    /**
     * Generate 404 response
     * @param string $content
     * @return Response
     */
    public static function notFound($content = null)
    {
        return new self(Response::notFound($content));
    }


    /**
     * Generate 403 response
     * @param string $content
     * @return Response
     */
    public static function forbidden($content = null)
    {
        return new self(Response::forbidden($content));
    }


    /**
     * Generate json response
     * @param array $data
     * @return Response
     */
    public static function json(array $data)
    {
        return new self(Response::json($data));
    }


    /**
     * Generate template response
     * @param string $template
     * @param array $data
     * @return Response
     */
    public static function view($template, array $data = [])
    {
        return new self(Response::view($template, $data));
    }


    /**
     * Generate downloadable file response
     * @param string $filename
     * @return Response
     */
    public static function download($filename)
    {
        return new self(Response::download($filename));
    }


    /**
     * Generate redirect response
     * @param string $url
     * @param bool $outside
     * @return Response
     */
    public static function redirect($url, $outside = false)
    {
        return new self(Response::redirect($url, $outside));
    }

} 