<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft;

use craft\core\Dispatcher;
use craft\core\handlers\Router;
use craft\core\handlers\Builder;
use craft\core\handlers\Caller;
use craft\core\handlers\Engine;
use craft\Bag;

class App extends Dispatcher
{

    /**
     * Setup router and URI protocol
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        // setup dispatcher
        parent::__construct([
            'router'    => new Router($routes),
            'builder'   => new Builder(),
            'caller'    => new Caller(),
            'engine'    => new Engine()
        ]);

        // put context in ze bag
        $this->on('start', function($context){
            Bag::set('context', $context);
        });
    }


    /**
     * Main process
     * @return mixed
     */
    public function plug()
    {
        // resolve protocol query
        $query = $_SERVER['REQUEST_URI'];
        $query = substr($query, strlen(APP_URL));
        $query = parse_url($query, PHP_URL_PATH);

        // start process
        return $this->query($query);
    }

}