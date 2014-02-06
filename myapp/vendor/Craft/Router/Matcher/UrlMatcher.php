<?php

namespace Craft\Router\Matcher;

use Craft\Router\Matcher;
use Craft\Router\Route;

class UrlMatcher extends Matcher
{

    /** @var array */
    public $elements = [
        'args'  => ':$1',
        'env'   => '+$1'
    ];

    /**
     * Find route
     * @param string $query
     * @param array $context
     * @param mixed $fallback
     * @return Route
     */
    public function find($query, array $context = [], $fallback = false)
    {
        // clean query
        $query = '/' . ltrim($query, '/');

        foreach($this->router->all() as $route)
        {
            // make pattern
            $pattern = '/' . ltrim($route->path, '/');
            $pattern = str_replace('/', '\/', $pattern);
            foreach($this->elements as $label => $element) {
                $segment = str_replace('\\$1', '([a-z_]+)', preg_quote($element));
                $pattern = preg_replace('/' . $segment . '/', '(?<' . $label . '__${1}>[^\/]+)', $pattern);
            }
            $pattern = '/^' . $pattern . '$/';

            // compare
            if(preg_match($pattern, $query, $out)){

                // check context
                $valid = true;
                foreach($context as $key => $value) {
                    if(!isset($route->context[$key]) or $route->context[$key] != $value) {
                        $valid = false;
                    }
                }

                // not valid
                if(!$valid) {
                    continue;
                }

                // clean data
                $data = [];
                unset($out[0]);
                foreach($out as $key => $value) {
                    foreach($this->elements as $label => $element) {
                        $innerlabel = $label . '__';
                        if(substr($key, 0, strlen($innerlabel)) == $innerlabel) {
                            $cleankey = str_replace($innerlabel, '', $key);
                            $data[$label][$cleankey] = $value;
                        }
                    }
                }

                $route->data = $data;
                return $route;
            }
        }

        return $fallback;
    }


}