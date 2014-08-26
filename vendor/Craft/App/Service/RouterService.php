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
use Craft\Map\RouterInterface;
use Craft\Trace\Logger;

class RouterService extends App\Service
{

    /** @var RouterInterface */
    public $router;


    /**
     * Init with routes or router
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
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
     * @throws App\Error\NotFound
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
            throw new App\Error\NotFound('Route ' . $request->query . ' not found');
        }

        // update request
        $request->action = $route->action;
        $request->args = $route->data;
        $request->meta = array_merge($request->meta, $route->meta);

        Logger::info('Route ' . $route->from . ' matched');
    }

}