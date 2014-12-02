<?php

namespace Craft\Web\Api;

use Craft\Web;
use Craft\App;
use Craft\Router;

/**
 * Handle url routing, param mapping and json rendering
 */
class Handler extends Web\App\Handler
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