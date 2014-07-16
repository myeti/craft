<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Forge;

use Craft\BOx\Env as EnvProvider;
use Craft\Data\Provider;
use Craft\Data\Provider\Container;
use Craft\Data\Provider\Container\Swap;

abstract class Env extends Container
{

    use Swap;

    /**
     * Create provider instance
     * @return EnvProvider
     */
    protected static function bind()
    {
        return new EnvProvider;
    }


    /**
     * Get or set timezone
     * @param string $timezone
     * @return string
     */
    public static function timezone($timezone = null)
    {
        return static::instance()->timezone($timezone);
    }


    /**
     * Get or set locale
     * @param $lang
     * @return string
     */
    public static function locale($lang = null)
    {
        return static::instance()->locale($lang);
    }

} 