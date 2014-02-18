<?php

namespace Craft\Env;

use Craft\Data\Provider;
use Craft\Data\StaticProvider;

abstract class Adapter extends StaticProvider
{

    /**
     * Swap provider
     * @param Provider $provider
     */
    public static function provider(Provider $provider)
    {
        static::instance($provider);
    }

} 