<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box\pattern;

trait Multiton
{

    /** @var self[] */
    protected static $_instances = [];


    /**
     * Private constructors
     */
    protected function __construct() {}
    protected function __clone() {}


    /**
     * Get singleton instance by name
     * @param $name
     * @return Multiton
     */
    public function instance($name)
    {
        // lazy init
        if(!isset(static::$_instances[$name])) {
            static::$_instances[$name] = new self();
        }

        return static::$_instances[$name];
    }

} 