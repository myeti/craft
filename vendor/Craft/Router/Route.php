<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Router;

class Route
{

    /** @var string */
    public $query;

    /** @var array */
    public $args = [];

    /** @var callable */
    public $action;

    /** @var array */
    public $rules = [];


    /**
     * Define route
     * @param string $query
     * @param mixed $action
     * @param array $rules
     */
    public function __construct($query = null, $action = null, array $rules = [])
    {
        $this->query = $query;
        $this->action = $action;
        $this->rules = $rules;
    }

} 