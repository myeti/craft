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
    public $name;

    /** @var string */
    public $from;

    /** @var mixed */
    public $to;

    /** @var array */
    public $customs = [];

    /** @var array */
    public $data = [];

    /**
     * Define route
     * @param $from
     * @param $to
     */
    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

} 