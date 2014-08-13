<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Data;

class Provider extends \ArrayObject implements ProviderInterface
{

    /**
     * Get all elements
     * @return array
     */
    public function all()
    {
        return $this->getArrayCopy();
    }


    /**
     * Check if element exists
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this[$key]);
    }


    /**
     * Get element by key, fallback on error
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        return isset($this[$key]) ? $this[$key] : $fallback;
    }


    /**
     * Set element by key with value
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        $this[$key] = $value;
        return $this;
    }


    /**
     * Drop element by key
     * @param string $key
     * @return $this
     */
    public function drop($key)
    {
        unset($this[$key]);
        return $this;
    }


    /**
     * Clear data
     * @return $this
     */
    public function clear()
    {
        $this->exchangeArray([]);
        return $this;
    }

}