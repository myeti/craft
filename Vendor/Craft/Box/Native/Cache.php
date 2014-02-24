<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box\Native;

use Craft\Data\Provider;

class Cache implements Provider
{

    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return apc_exists($key);
    }

    /**
     * Get element by key, fallback on error
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        return apc_exists($key) ? apc_fetch($key) : $fallback;
    }

    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        return apc_store($key, $value);
    }

    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key)
    {
        return apc_delete($key);
    }


    /**
     * Clear all items
     * @return bool
     */
    public function clear()
    {
        return apc_clear_cache();
    }

}