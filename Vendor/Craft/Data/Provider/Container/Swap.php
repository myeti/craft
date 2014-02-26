<?php

namespace Craft\Data\Provider\Container;

use Craft\Data\Provider;

trait Swap
{

    /**
     * Change provider
     * @param Provider $provider
     */
    public static function swap(Provider $provider)
    {
        static::instance($provider);
    }

} 