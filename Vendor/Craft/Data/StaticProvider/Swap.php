<?php

namespace Craft\Data\StaticProvider;

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