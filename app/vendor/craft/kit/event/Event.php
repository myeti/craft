<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\event;

class Event extends \ArrayObject
{

    /** @var string */
    public $name;


    /**
     * Setup Event object
     * @param $name
     * @param array $args
     */
    public function __construct($name, array $args = [])
    {
        $this->name = $name;
        parent::__construct($args);
    }

} 