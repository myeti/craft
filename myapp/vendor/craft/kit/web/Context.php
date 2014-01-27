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

use craft\box\pattern\chain\Material;
use craft\kit\router\Route;
use craft\kit\web\context\View;
use craft\kit\web\context\Action;

class Context implements Material
{

    /** @var string */
    public $query;

    /** @var bool */
    public $service;

    /** @var int */
    public $error;

    /** @var Route */
    public $route;

    /** @var Action */
    public $action;

    /** @var View */
    public $view;

    /**
     * Default values
     */
    public function __construct()
    {
        $this->route = new Route();
        $this->action = new Action();
        $this->view = new View();
    }

}