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

use Craft\Box\Mog;
use Craft\Box\Context;
use Craft\Router\Route;

/**
 * The Request object contains
 * all the data given from route
 */
class Request
{

    /** @var float */
    public $time;

    /** @var string */
    public $query;

    /** @var array */
    public $args = [];

    /** @var callable */
    public $action;

    /** @var array */
    public $params = [];

    /** @var array */
    public $meta = [];

    /** @var Route */
    public $route;

    /** @var string */
    public $error;

    /** @var Context */
    public $context;


    /**
     * New request
     */
    public function __construct($query = null)
    {
        $this->query = $query;
        $this->route = new Route;
        $this->time = microtime(true);
        $this->context = Mog::context();
    }

} 