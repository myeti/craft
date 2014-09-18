<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Routing;

class UrlRouter implements RouterInterface
{

    /** @var Route[] */
    protected $routes = [];

    /** @var callable[] */
    protected $callbacks = [];

    /** @var array */
    protected $prefixes = [];


    /**
     * Setup matcher
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        foreach($routes as $query => $action) {
            if($action instanceof Route) {
                $this->add($action);
            }
            else {
                $this->map($query, $action);
            }
        }
    }


    /**
     * Make route query path
     * @param string $query
     * @param mixed $action
     * @param array $rules
     * @return $this
     */
    public function map($query, $action, array $rules = [])
    {
        return $this->add(new Route($query, $action, $rules));
    }


    /**
     * Add route
     * @param Route $route
     * @return $this
     */
    public function add(Route $route)
    {
        // add prefixes
        $route->query = implode(null, $this->prefixes) . $route->query;
        $this->routes[$route->query] = $route;
        return $this;
    }


    /**
     * Group routes
     * @param string $base
     * @param callable $group
     * @param callable $callback
     * @return $this
     */
    public function group($base, callable $group, callable $callback = null)
    {
        // add grouped prefix and callback
        array_push($this->prefixes, $base);
        if($callback) {
            array_push($this->callbacks, $callback);
        }

        // execute group
        $group($this);

        // add grouped prefix and callback
        array_pop($this->prefixes);
        if($callback) {
            array_pop($this->callbacks);
        }

        return $this;
    }


    /**
     * Get route
     * @param $path
     * @return Route
     */
    public function route($path)
    {
        return isset($this->routes[$path])
            ? $this->routes[$path]
            : false;
    }


    /**
     * Get all routes
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
        // resolve query & context
        list($query, $context) = $this->resolve($query, $context);

        // search in all routes
        foreach($this->routes as $route)
        {
            // compile pattern
            $pattern = $this->compile($route->query);

            // compare pattern
            if(preg_match($pattern, $query, $args)){

                // clean args
                $args = $this->clean($args);

                // rules matching
                if(!$this->check($route, $args, $context)) {
                    continue;
                }

                // update route
                $route->args = $args;

                return $route;
            }
        }

        return false;
    }


    /**
     * Resolve query
     * @param string $query
     * @param array $context
     * @return array
     */
    protected function resolve($query, array $context = [])
    {
        // has http method
        $method = null;
        if(preg_match('#^(GET|POST|PUT|DELETE|OPTIONS|HEAD) (.*)$#', $query, $data)) {
            $method = $data[1];
            $query = $data[2];
        }

        // clean query
        $query = '/' . trim($query, '/');

        // update context
        if($method xor (!$method and !isset($context['method']))) {
            $context['method'] = $method;
        }

        return [$query, $context];
    }


    /**
     * Compile query into regex
     * @param string $query
     * @return string
     */
    protected function compile($query)
    {
        $pattern = str_replace('/', '\/', $query);
        $pattern = preg_replace('#\:(\w+)#', '(?P<$1>(.+))', $pattern);
        return '#^' . $pattern . '$#';
    }


    /**
     * Check context
     * @param Route $route
     * @param array $context
     * @return bool
     */
    protected function check(Route $route, array $context = [])
    {
        return (array_intersect_assoc($context, $route->rules) == $route->rules);
    }


    /**
     * Clean args
     * @param array $args
     * @return array
     */
    protected function clean(array $args)
    {
        // remove int keys
        foreach($args as $key => $null) {
            if(is_int($key)) {
                unset($args[$key]);
            }
        }

        return $args;
    }

}