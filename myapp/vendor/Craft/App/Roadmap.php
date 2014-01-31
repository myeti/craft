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

use Craft\App\Roadmap\Draft;
use Craft\App\Roadmap\Sketch;
use Craft\App\Router\Route;
use Craft\Pattern\Chain\Material;
use Craft\Pattern\Specification\Item;

class Roadmap implements Material, Item
{

    /** @var string */
    public $query;

    /** @var bool */
    public $service;

    /** @var int */
    public $error;

    /** @var Route */
    public $route;

    /** @var Draft */
    public $draft;

    /** @var Sketch */
    public $sketch;

    /**
     * Default values
     */
    public function __construct()
    {
        $this->route = new Route();
        $this->draft = new Draft();
        $this->sketch = new Sketch();
    }

} 