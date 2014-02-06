<?php

namespace Craft\Router;

abstract class Matcher
{

    /** @var RouteProvider */
    protected $router;


    /**
     * Setup matcher with router
     * @param RouteProvider $router
     */
    public function __construct(RouteProvider &$router)
    {
        $this->router = $router;
    }


    /**
     * Get router
     * @return RouteProvider
     */
    public function router()
    {
        return $this->router;
    }


    /**
     * Find route
     * @param string $query
     * @param array $context
     * @param mixed $fallback
     * @return Route
     */
    abstract public function find($query, array $context = [], $fallback = null);

} 