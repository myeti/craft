<?php

namespace Craft\Box;

use Craft\Data\Provider;
use Craft\Data\StaticProvider;
use Craft\Data\StaticProvider\Swap;

abstract class Cache extends StaticProvider
{

    use Swap;

    /**
     * Create provider instance
     * @return Provider
     */
    protected static function bind()
    {
        return new Native\Cache();
    }

} 