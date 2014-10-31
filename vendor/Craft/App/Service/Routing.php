<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Service;

use Craft\App;
use Craft\Box\Mog;
use Craft\Router;
use Craft\Debug\Logger;

class Routing extends App\Service
{

    /** @var Router\Seeker */
    public $router;


    /**
     * Init with routes or router
     * @param Router\Seeker $router
     */
    public function __construct(Router\Seeker $router)
    {
        $this->router = $router;
    }


    /**
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return ['kernel.request' => 'onKernelRequest'];
    }


    /**
     * Handle request
     * @param App\Request $request
     * @throws App\Internal\NotFound
     */
    public function onKernelRequest(App\Request $request)
    {
        // set query
        if(!$request->query) {
            $request->query = Mog::query();
        }

        // route query
        $route = $this->router->find($request->query);

        // 404
        if(!$route) {
            throw new App\Internal\NotFound('Route ' . $request->query . ' not found');
        }

        // update request
        $request->route = $route;
        $request->args = $route->args;
        $request->params = $route->args;
        $request->action = $route->action;

        Logger::info('Route ' . $route->query . ' matched');
    }

}