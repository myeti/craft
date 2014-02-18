<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Pattern;

use Craft\Error\NotImplementedException;

trait StaticSingleton
{

    /**
     * Get singleton instance
     * @throws NotImplementedException
     * @return mixed;
     */
    protected static function instance($newInstance = null)
    {
        static $instance;
        if($newInstance) {
            $instance = $newInstance;
        }
        if(!$instance) {
            $instance = static::defaultInstance();
        }

        return $instance;
    }


    /**
     * Create instance
     * @return null
     */
    protected static function defaultInstance()
    {
        return new NotImplementedException('You must override this method.');
    }

} 