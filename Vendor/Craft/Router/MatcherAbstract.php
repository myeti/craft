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

abstract class MatcherAbstract implements MatcherInterface
{

    /** @var Route[] */
    protected $routes = [];

    /**
     * Setup matcher
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        foreach($routes as $from => $to) {
            if($to instanceof Route) {
                $this->add($to);
            }
            else {
                $this->map($from, $to);
            }
        }
    }


    /**
     * Make route from path
     * @param string $from
     * @param mixed $to
     * @param array $customs
     * @return $this
     */
    public function map($from, $to, array $customs = [])
    {
        return $this->add(new Route($from, $to, $customs));
    }


    /**
     * Add route
     * @param Route $route
     * @return $this
     */
    public function add(Route $route)
    {
        $this->routes[$route->from] = $route;
        return $this;
    }


    /**
     * Bind an inner router
     * @param string $base
     * @param MatcherInterface $matcher
     * @return $this
     */
    public function bind($base, MatcherInterface $matcher)
    {
        foreach($matcher->routes() as $route) {
            $route->from = $base . $route->from;
            $this->add($route);
        }
        return $this;
    }


    /**
     * Get all routes
     * @return Route[]
     */
    public function routes()
    {
        return $this->routes;
    }

} 