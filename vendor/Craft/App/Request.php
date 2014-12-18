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
use Craft\Kit\Runnable;
use Craft\Router\Route;

class Request extends Http\Request implements RequestInterface
{

    /** @var Runnable */
    protected $action;

    /** @var Route */
    protected $route;

    /** @var string */
    protected $error;


    /**
     * Get or Set action
     * @param Runnable $action
     * @return Runnable
     */
    public function action(Runnable $action = null)
    {
        if($action) {
            $this->action = $action;
        }

        return $this->action;
    }


    /**
     * Get or Set route
     * @param Route $route
     * @return Route
     */
    public function route(Route $route = null)
    {
        if($route) {
            $this->route = $route;
        }

        return $this->route;
    }


    /**
     * Get or Set error
     * @param $message
     * @return string
     */
    public function error($message = null)
    {
        if($message) {
            $this->error = $message;
        }

        return $this->error;
    }

} 