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

    /** @var string */
    protected $_protocol;


    /**
     * Setup router and URI protocol
     * @param array $routes
     * @param string $protocol
     */
    public function __construct(array $routes, $protocol = 'PATH_INFO')
    {
        // set uri protocol
        $this->_protocol = strtoupper($protocol);

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
        $query = isset($_SERVER[$this->_protocol])
            ? rtrim($_SERVER[$this->_protocol], '/')
            : '/';

        // start process
        return $this->query($query);
    }

}