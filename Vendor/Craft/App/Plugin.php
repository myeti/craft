<?php

namespace Craft\App;

abstract class Plugin
{

    /**
     * Before execution
     * @param Request $request
     * @return Request
     */
    public function before(Request $request)
    {
        return $request;
    }

    /**
     * After execution
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function after(Request $request, Response $response)
    {
        return $response;
    }

} 