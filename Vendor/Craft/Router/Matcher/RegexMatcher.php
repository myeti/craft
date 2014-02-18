<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Router\Matcher;

use Craft\Router\Matcher;
use Craft\Router\Route;

abstract class RegexMatcher extends Matcher
{

    /**
     * Find route
     * @param string $query
     * @param array $context
     * @param mixed $fallback
     * @return Route
     */
    public function find($query, array $customs = [], $fallback = false)
    {
        // prepare query
        $query = $this->prepare($query);

        // search in all routes
        foreach($this->map->all() as $route)
        {
            // compile pattern
            $pattern = $this->compile($route->from);

            // compare
            if(preg_match($pattern, $query, $out)){

                // strip first line
                unset($out[0]);

                // check context
                if(array_intersect_assoc($customs, $route->customs) != $route->customs) {
                    continue;
                }

                // parse data
                $data = $this->parse($out);

                $route->data = $data;
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
     * Compile path into regex
     * @param $path
     * @return string
     */
    abstract protected function compile($path);


    /**
     * Parse results
     * @param array $results
     * @return array
     */
    abstract protected function parse(array $results);


}