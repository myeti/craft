<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\web;

use craft\box\env\Mog;
use craft\box\env\Bag;
use craft\kit\event\Event;
use craft\kit\dispatcher\Dispatcher;
use craft\kit\router\Route;
use craft\kit\web\process\RouterHandler;
use craft\kit\web\process\ResolverHandler;
use craft\kit\web\process\FirewallHandler;
use craft\kit\web\process\CallerHandler;
use craft\kit\web\process\PresenterHandler;

class App extends Dispatcher
{

    /**
     * Setup router & handlers
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        // define handlers
        $handlers = [
            new RouterHandler($routes),
            new ResolverHandler(),
            new FirewallHandler(),
            new CallerHandler(),
            new PresenterHandler()
        ];

        // setup dispatcher
        parent::__construct($handlers);
    }


    /**
     * Dispatch from action, skip routing
     * @param $action
     * @param array $args
     * @param bool $service
     * @return mixed
     */
    public function forward($action, array $args = [], $service = false)
    {
        // init route
        $route = new Route();
        $route->target = $action;
        $route->args = $args;

        // init env
        $context = new Context();
        $context->query = '@' . $action;
        $context->service = (bool)$service;
        $context->route = $route;

        // start process, skip routing
        return $this->run($context, ['router']);
    }


    /**
     * Main process
     * @param null $query
     * @param bool $service
     * @return mixed
     */
    public function plug($query = null, $service = false)
    {
        // resolve protocol query
        if(!$query) {
            $query = Mog::url();
            $query = substr($query, strlen(Mog::base()));
            $query = parse_url($query, PHP_URL_PATH);
        }

        // create context
        $context = new Context();
        $context->query = (string)$query;
        $context->service = (bool)$service;

        // start process
        return $this->run($context);
    }

}