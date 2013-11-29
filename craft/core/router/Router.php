<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\core\router;

class Router
{

    /** @var array  */
    protected $_routes = [];


    /**
     * Setup with routes
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        $this->_routes = $routes;
    }


    /**
     * Pattern matching
     * @param $query
     * @return bool|Route
     */
    public function find($query)
    {
        foreach($this->_routes as $prepattern => $target)
        {
            // make pattern
            $pattern = str_replace('/', '\/', $prepattern);
            $pattern = preg_replace('/(:([a-z_]+))/', '(?<${1}>[^/]+)', $pattern);
            $pattern = preg_replace('/(\+([a-z_]+))/', '(?<env_${1}>[^/]+)', $pattern);
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
                    if(substr($key, 0, 4) == 'env_'){
                        $env[substr($key, 4)] = $args[$key];
                        unset($args[$key]);
                    }

                }

                // make route
                $route = new Route();
                $route->query = $query;
                $route->target = $target;
                $route->args = $args;
                $route->env = $env;

                return $route;
            }
        }

        return false;
    }


    /**
     * Backward finder
     * @param $label
     * @param array $args
     * @return bool|string
     */
    public function resolve($label, array $args = [])
    {
        foreach($this->_routes as $prepattern => $target){
            if($target === $label){

                // make url
                foreach($args as $key => $value){
                    $prepattern = str_replace(':'.$key, $value, $prepattern);
                }

                return $prepattern;
            }
        }

        return false;
    }

}