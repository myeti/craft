<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Box\Data;

use Craft\Box\Data\Provider;

class ArrayProvider extends \ArrayObject implements Provider
{

    /**
     * Init array
     * @param array $set
     */
    public function __construct(array &$set = [])
    {
        parent::__construct($set);
    }

    /**
     * Check if element exists
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this[$key]);
    }

    /**
     * Get element by key, fallback on error
     * @param $key
     * @param null $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        return isset($this[$key]) ? $this[$key] : $fallback;
    }

    /**
     * Set element by key with value
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        $this[$key] = $value;
        return true;
    }

    /**
     * Drop element by key
     * @param $key
     * @return bool
     */
    public function drop($key)
    {
        unset($this[$key]);
        return true;
    }

}