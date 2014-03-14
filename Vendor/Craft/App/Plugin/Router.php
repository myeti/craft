<?php

namespace Craft\App\Plugin;

use Craft\App\Event\NotFound;
use Craft\App\Handler\Before;
use Craft\App\Request;
use Craft\Router\Basic;

class Router extends Before
{

    /** @var Basic */
    protected $router;

    /**
     * Setup router
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        $this->router = new Basic($routes);
    }

    /**
     * Handle request
     * @param Request $request
     * @throws \Craft\App\Event\NotFound
     * @return Request
     */
    public function handle(Request $request)
    {
        // route query
        $route = $this->router->find($request->query);

        // 404
        if(!$route) {
            throw new NotFound('Route "' . $request->query . '" not found.');
        }

        // update request
        $request->action = $route->to;
        $request->args = $route->data;

        return $request;
    }

}