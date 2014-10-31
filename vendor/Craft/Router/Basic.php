<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Router;

class Basic implements Seeker
{

    /** @var Route[] */
    protected $routes = [];


    /**
     * Init with routes
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        foreach($routes as $query => $action) {
            $this->add(new Route($query, $action));
        }
    }


    /**
     * Add route
     * @param Route $route
     * @return $this
     */
    public function add(Route $route)
    {
        $this->routes[$route->query] = $route;
        return $this;
    }


    /**
     * Get routes
     * @return Route[]
     */
    public function routes()
    {
        return $this->routes;
    }


    /**
     * Find route
     * @param string $query
     * @param array $context
     * @return Route
     */
    public function find($query, array $context = [])
    {
        if(isset($this->routes[$query])) {
            return $this->routes[$query];
        }

        return false;
    }

}