<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\core;

use craft\core\data\Route;
use craft\core\data\Build;
use craft\core\data\View;

class Context
{

    /** @var string */
    public $query;

    /** @var bool */
    public $service;

    /** @var Route */
    public $route;

    /** @var Build */
    public $build;

    /** @var mixed */
    public $data;

    /** @var View */
    public $view;

    /** @var string */
    public $content;

} 