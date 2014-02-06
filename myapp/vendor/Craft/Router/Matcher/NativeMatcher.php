<?php

namespace Craft\Router\Matcher;

use Craft\Router\Matcher;
use Craft\Router\Route;

class NativeMatcher extends Matcher
{

    /**
     * Find route
     * @param string $query
     * @param array $context
     * @param mixed $fallback
     * @return Route
     */
    public function find($query, array $context = [], $fallback = false)
    {
        foreach($this->router->all() as $route) {
            if($query == $route->path) {
                return $route;
            }
        }

        return $fallback;
    }

}