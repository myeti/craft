<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Env;

use Craft\Data\Repository;
use Craft\Data\StaticProvider;
use Craft\Env\Adapter\NativeConfig;

abstract class Config extends Adapter
{

    /**
     * Create provider instance
     * @return NativeConfig
     */
    protected static function defaultInstance()
    {
        return new NativeConfig();
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