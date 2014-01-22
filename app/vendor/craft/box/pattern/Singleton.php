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

trait Singleton
{

    /** @var Singleton */
    protected static $_instance;


    /**
     * Private constructors
     */
    protected function __construct() {}
    protected function __clone() {}


    /**
     * Get singleton instance
     * @return Singleton
     */
    public function instance()
    {
        // lazy init
        if(!static::$_instance) {
            static::$_instance = new self();
        }

        return static::$_instance;
    }

} 