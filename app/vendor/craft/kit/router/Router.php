<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\router;

use craft\box\data\provider\Provider;

/**
 * Class Router
 * Find a stored route depending on the query
 */
class Router extends \ArrayObject implements Provider
{

    /**
     * Forward query
     * @param string $query
     * @param mixed $fallback
     * @return bool|Route
     */
    public function find($query, $fallback = false)
    {
        foreach($this as $rule => $target)
        {
            // make pattern
            $pattern = str_replace('/', '\/', $rule);
            $pattern = preg_replace('/(:([a-z_]+))/', '(?<${2}>[^\/]+)', $pattern);
            $pattern = preg_replace('/(\+([a-z_]+))/', '(?<env_${2}>[^\/]+)', $pattern);
            $pattern = '/^' . $pattern . '$/';

            // compare
            if(preg_match($pattern, $query, $args)){

                // clean args
                $env = [];
                foreach($args as $key => $value){

                    // clear int
                    if(is_int($key)){
                        unset($args[$key]);
                    }
                    // clear env
                    elseif(substr($key, 0, 4) == 'env_'){
                        $env[substr($key, 4)] = $args[$key];
                        unset($args[$key]);
                    }

                }

                // make route
                $route = new Route();
                $route->query = $query;
                $route->rule = $rule;
                $route->target = $target;
                $route->args = $args;
                $route->env = $env;

                return $route;
            }
        }

        return $fallback;
    }


    /**
     * Check if element exists
     * @param $route
     * @return bool
     */
    public function has($route)
    {
        return isset($this[$route]);
    }


    /**
     * Get raw route
     * @param $route
     * @param null $fallback
     * @return mixed
     */
    public function get($route, $fallback = null)
    {
        return isset($this[$route]) ? $this[$route] : $fallback;
    }


    /**
     * Set element by key with value
     * @param $route
     * @param $action
     * @return bool
     */
    public function set($route, $action)
    {
        $this[$route] = $action;
    }


    /**
     * Drop element by key
     * @param $route
     * @internal param $key
     * @return bool
     */
    public function drop($route)
    {
        unset($this[$route]);
    }

}