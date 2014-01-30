<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Handler;

use Craft\App\Handler;
use Craft\App\Roadmap;
use Craft\App\Router as AppRouter;
use Craft\Context\Env;
use Craft\Box\Error\SomethingIsWrongException;

/**
 * Class Router
 * Find route with query
 */
class Router extends Handler
{

    /** @var AppRouter */
    protected $router;


    /**
     * Create router
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        $this->router = new AppRouter($routes);
    }


    /**
     * Get handler name
     * @return string
     */
    public function name()
    {
        return 'router';
    }


    /**
     * Handle routing
     * @param Roadmap $roadmap
     * @throws SomethingIsWrongException
     * @return Roadmap
     */
    public function handleRoadmap(Roadmap $roadmap)
    {
        // get query
        $roadmap->query = '/' . ltrim($roadmap->query, '/');

        // route
        $route = $this->router->find($roadmap->query);

        // 404
        if(!$route){
            $roadmap->error = 404;
            throw new SomethingIsWrongException('Route "' . $roadmap->query . '" not found.', 404);
        }

        // env env
        foreach($route->env as $key => $value){
            Env::set($key, $value);
        }

        // set route
        $route->query = $roadmap->query;
        $roadmap->route = $route;

        return $roadmap;
    }

}