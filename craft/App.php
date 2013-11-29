<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 *
 * @author Aymeric Assier <aymeric.assier@gmail.com>
 * @date 2013-09-12
 * @version 0.1
 */
namespace craft;

use craft\Dispatcher;
use craft\Router;
use craft\Builder;

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
        parent::__construct($router, new Builder());
        $this->_protocol = strtoupper($protocol);
    }


    /**
     * Main process
     */
    public function handle($query = null)
    {
        // resolve query
        if(!$query) {
            $query = isset($_SERVER[$this->_protocol]) ? $_SERVER[$this->_protocol] : '/';
        }

        // start process
        parent::handle($query);
    }

}