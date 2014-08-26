<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Map;

class Route
{

    /** @var string */
    public $from;

    /** @var callable */
    public $action;

    /** @var array */
    public $context = [];

    /** @var array */
    public $data = [];

    /** @var array */
    public $meta = [];


    /**
     * Define route
     * @param string $from
     * @param string|callable $action
     * @param array $context
     */
    public function __construct($from, $action, array $context = [])
    {
        $this->from = $from;
        $this->action = $action;
        $this->context = $context;
    }

} 