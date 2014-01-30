<?php

namespace Craft\Pattern;

use Craft\Box\Error\NotImplementedException;

trait StaticSingleton
{

    /**
     * Get singleton instance
     * @throws NotImplementedException
     * @return mixed;
     */
    protected static function instance()
    {
        static $instance;
        if(!$instance) {

            // not implemented
            if(!method_exists(get_called_class(), 'createInstance')) {
                throw new NotImplementedException('You must implement createInstance() method.');
            }

            $instance = static::createInstance();
        }

        return $instance;
    }

} 