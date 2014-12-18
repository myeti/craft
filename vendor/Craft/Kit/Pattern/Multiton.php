<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Kit\Pattern;

trait Multiton
{

    /** @var static[] */
    protected static $instances = [];


    /**
     * Private constructors
     */
    protected function __construct() {}
    protected function __clone() {}


    /**
     * Get singleton instance by name
     * @param string $name
     * @return Multiton
     */
    public static function instance($name = 'master')
    {
        // lazy init
        if(!isset(static::$instances[$name])) {
            static::$instances[$name] = static::createInstance($name);
        }

        return static::$instances[$name];
    }


    /**
     * Create instance
     * @param string $name
     * @return Multiton
     */
    protected static function createInstance($name)
    {
        return new self;
    }

} 