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

use Craft\Debug\Error\NotImplemented;

trait StaticSingleton
{

    /**
     * Get static singleton instance
     * @param mixed $newInstance
     * @return mixed;
     */
    protected static function instance($newInstance = null)
    {
        static $instance;
        if($newInstance) {
            $instance = $newInstance;
        }
        if(!$instance) {
            $instance = static::createInstance();
        }

        return $instance;
    }


    /**
     * Create instance
     * @throws NotImplemented
     * @return null
     */
    protected static function createInstance()
    {
        throw new NotImplemented('You must override this method.');
    }

} 