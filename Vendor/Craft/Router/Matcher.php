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

abstract class Matcher
{

    /** @var Map */
    protected $map;


    /**
     * Setup matcher with router
     * @param Map $map
     */
    public function __construct(Map $map)
    {
        $this->map = $map;
    }


    /**
     * Get router
     * @return Map
     */
    public function &map()
    {
        return $this->map;
    }


    /**
     * Find route
     * @param string $query
     * @param array $customs
     * @param mixed $fallback
     * @return Route
     */
    abstract public function find($query, array $customs = [], $fallback = null);

} 