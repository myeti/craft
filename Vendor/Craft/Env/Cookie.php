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

use Craft\Env\Adapter\NativeCookie;

abstract class Cookie extends Adapter
{

    /**
     * Create provider instance
     * @return NativeCookie
     */
    protected static function defaultInstance()
    {
        return new NativeCookie();
    }

} 