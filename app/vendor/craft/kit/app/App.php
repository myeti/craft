<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\app;

use craft\box\env\Mog;
use craft\box\env\Bag;
use craft\kit\event\Event;
use craft\kit\dispatcher\Dispatcher;
use craft\kit\router\Route;
use craft\kit\app\process\RouterHandler;
use craft\kit\app\process\ResolverHandler;
use craft\kit\app\process\FirewallHandler;
use craft\kit\app\process\CallerHandler;
use craft\kit\app\process\PresenterHandler;

class App extends Dispatcher
{

    /**
     * Setup router & handlers
     * @param array $routes
     * @param array $handlers
     */
    public function __construct(array $routes, array $handlers = [])
    {
        // define handlers
        $handlers = array_merge([
            'router'        => new RouterHandler($routes),
            'resolver'      => new ResolverHandler(),
            'firewall'      => new FirewallHandler(),
            'caller'        => new CallerHandler(),
            'presenter'     => new PresenterHandler()
        ], $handlers);

        // setup dispatcher
        parent::__construct($handlers);

        // put env in ze bag
        $this->on('start', function(Event $e){
            Bag::set('env', $e['input']);
        });
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