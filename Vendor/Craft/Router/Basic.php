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

class Basic extends Matcher
{

    /**
     * Find route
     * @param string $query
     * @param array $customs
     * @param mixed $fallback
     * @return Route
     */
    public function find($query, array $customs = [], $fallback = false)
    {
        // prepare query
        $query = $this->prepare($query);

        // strict comparison
        foreach($this->routes as $route) {

            // route filter
            $route = $this->filter($route);

            // match !
            if($query == $route->from) {
                return $route;
            }
        }

        return $fallback;
    }


    /**
     * Prepare query
     * @param $query
     * @return string
     */
    protected function prepare($query)
    {
        return trim($query);
    }


    /**
     * Apply user filter on route
     * @param Route $route
     * @return Route
     */
    protected function filter(Route $route)
    {
        return $route;
    }

}