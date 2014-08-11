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

class Repository extends \ArrayObject implements ProviderInterface
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
        list($item, $key) = Collection::resolve($key, $this);
        return isset($item[$key]);
    }


    /**
     * Get element by key, fallback on error
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    public function get($key, $fallback = null)
    {
        list($item, $key) = Collection::resolve($key, $this);
        return isset($item[$key]) ? $item[$key] : $fallback;
    }


    /**
     * Set element by key with value
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value)
    {
        list($item, $key) = Collection::resolve($key, $this, '.', true);
        $item[$key] = $value;
        return $this;
    }


    /**
     * Drop element by key
     * @param string $key
     * @return $this
     */
    public function drop($key)
    {
        list($item, $key) = Collection::resolve($key, $this);
        if(isset($item[$key])) {
            unset($item[$key]);
        }

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