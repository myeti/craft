<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Routing;

interface RouterInterface
{

    /**
     * Add route
     * @param Route $route
     */
    public function add(Route $route);

    /**
     * Get routes
     * @return Route[]
     */
    public function routes();

    /**
     * Find route
     * @param string $query
     * @param array $context
     * @return Route
     */
    public function find($query, array $context = []);

}