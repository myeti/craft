<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Context;

use Craft\Box\Data\StaticProvider;
use Craft\Box\Data\Repository;

abstract class Flash extends StaticProvider
{

    /**
     * Create provider instance
     * @return Repository
     */
    protected static function createInstance()
    {
        return Repository::from($_SESSION, 'craft.flash');
    }

    /**
     * Pull data
     * @param $key
     * @param null $fallback
     * @return mixed|void
     */
    public static function get($key, $fallback = null)
    {
        $data = parent::get($key, $fallback);
        static::drop($key);
        return $data;
    }

}