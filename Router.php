<?php

namespace craft;

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
     * @return bool|\stdClass
     */
    public function find($query)
    {
        foreach($this->_routes as $prepattern => $target)
        {
            // make pattern
            $pattern = str_replace('/', '\/', $prepattern);
            $pattern = preg_replace('/(:([a-z_]+))/', '(?<${1}>[^/]+)', $pattern);
            $pattern = '/' . $pattern . '/';

            // compare
            if(preg_match($pattern, $query, $out)){

                // clean args
                foreach($out as $key => $value){
                    if(is_int($key)){
                        unset($out[$key]);
                    }
                }

                // make route
                $route = new \stdClass();
                $route->query = $query;
                $route->target = $target;
                $route->args = $out;

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