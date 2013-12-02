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
use craft\core\handlers\Builder;
use craft\core\handlers\Caller;
use craft\core\handlers\Engine;

use craft\Router;
use craft\Bag;

class App extends Dispatcher
{

    /** @var string */
    protected $_protocol;


    /**
     * Setup router and URI protocol
     * @param Router $router
     * @param string $protocol
     */
    public function __construct(Router $router, $protocol = 'PATH_INFO')
    {
        $this->_protocol = strtoupper($protocol);

        parent::__construct([
            'router' => $router,
            'builder' => new Builder(),
            'caller' => new Caller(),
            'engine' => new Engine()
        ]);

        // put in zi bag
        $this->on('start', function($context){
            Bag::set('context', $context);
        });
    }


    /**
     * Main process
     * @param null|string $query
     * @param bool $service
     * @return mixed
     */
    public function handle($query = null, $service = false)
    {
        // resolve protocol query
        if(!$query) {
            $query = isset($_SERVER[$this->_protocol]) ? $_SERVER[$this->_protocol] : '/';
        }

        // start process
        return parent::handle($query, $service);
    }

}