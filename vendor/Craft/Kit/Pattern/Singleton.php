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

trait Singleton
{

    /** @var Singleton */
    protected static $instance;


    /**
     * Private constructors
     */
    protected function __construct() {}
    protected function __clone() {}


    /**
     * Get singleton instance
     * @return Singleton
     */
    public static function instance()
    {
        // lazy init
        if(!static::$instance) {
            static::$instance = static::createInstance();
        }

        return static::$instance;
    }


    /**
     * Create instance
     * @return Singleton
     */
    protected static function createInstance()
    {
        return new self;
    }

} 