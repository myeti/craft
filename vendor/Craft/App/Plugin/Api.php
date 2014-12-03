<?php

namespace Craft\App\Plugin;

use Craft\App;
use Craft\Router;

/**
 * Handle url routing, param mapping and json rendering
 */
class Api extends Web
{

    /**
     * Init Http Api Handler
     * @param Router\Seeker $router
     */
    public function __construct(Router\Seeker $router)
    {
        $this->router = $router;
    }


    /**
     * Handle response
     * @param App\Response $response
     * @param App\Request $request
     */
    public function onKernelResponse(App\Request $request, App\Response &$response = null)
    {
        $response = App\Response::json($response->content());
    }
}