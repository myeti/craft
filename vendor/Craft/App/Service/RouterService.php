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

use Craft\Error\NotFound;
use Craft\App\Service;
use Craft\App\Request;
use Craft\Map\RouterInterface;
use Craft\Log\Logger;

class RouterService extends Service
{

    /** @var string */
    public $name = 'Router';

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
     * Handle request
     * @param Request $request
     * @throws \Craft\Error\NotFound
     * @return Request
     */
    public function before(Request $request)
    {
        // route query
        $route = $this->router->find($request->query);

        // 404
        if(!$route) {
            throw new NotFound('Route "' . $request->query . '" not found');
        }

        // update request
        $request->before = $route->before;
        $request->action = $route->action;
        $request->after = $route->after;
        $request->args = $route->data;
        $request->meta = array_merge($request->meta, $route->meta);

        Logger::info('App.Routing : route "' . $route->from . '" found, request created');

        return $request;
    }

}