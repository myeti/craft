<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App;

use Craft\Http;
use Craft\Kit\Action;
use Craft\Router\Route;

interface RequestInterface extends Http\RequestInterface
{


    /**
     * Get or Set action
     * @param Action $action
     * @return Action
     */
    public function action(Action $action = null);


    /**
     * Get or Set route
     * @param Route $route
     * @return Route
     */
    public function route(Route $route = null);


    /**
     * Get or Set error
     * @param $message
     * @return string
     */
    public function error($message = null);

} 