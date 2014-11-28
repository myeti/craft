<?php

namespace Craft\App\Service;

use Craft\App;
use Craft\Debug\Error;
use Craft\Debug\Logger;
use Craft\Orm\Syn;
use Craft\Router;
use Craft\View;
use Craft\Kit\Action;

/**
 * Handle url routing, param mapping and json rendering
 */
class Api extends WebHandler
{

    /**
     * Init HttpHandler
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