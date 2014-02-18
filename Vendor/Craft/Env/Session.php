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

use Craft\Env\Adapter\NativeSession;

abstract class Session extends Adapter
{

    /**
     * Create provider instance
     * @return NativeSession
     */
    protected static function defaultInstance()
    {
        return new NativeSession('_craft.session');
    }

} 