<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft;

use Craft\App\Roadmap;
use Craft\App\Router\Route;
use Craft\App\Dispatcher;
use Craft\App\Handler\Caller;
use Craft\App\Handler\Firewall;
use Craft\App\Handler\Presenter;
use Craft\App\Handler\Resolver;
use Craft\App\Handler\Router;
use Craft\Context\Mog;

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
            new Router($routes),
            new Resolver(),
            new Firewall(),
            new Caller(),
            new Presenter()
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
        $roadmap = new Roadmap();
        $roadmap->query = '@' . $action;
        $roadmap->service = (bool)$service;
        $roadmap->route = $route;

        // start process, skip routing
        return $this->run($roadmap, ['router']);
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
        $roadmap = new Roadmap();
        $roadmap->query = (string)$query;
        $roadmap->service = (bool)$service;

        // start process
        return $this->run($roadmap);
    }

}