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

interface ProviderInterface
{

    /**
     * Get all elements
     * @return array
     */
    public function all();


    /**
     * Check if element exists
     * @param string $key
     * @return bool
     */
    public function has($key);


    /**
     * Get element by key, fallback on error
     * @param string $key
     * @param mixed $fallback
     * @return mixed
     */
    public function get($key, $fallback = null);


    /**
     * Set element by key with value
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value);


    /**
     * Drop element by key
     * @param string $key
     */
    public function drop($key);


    /**
     * Clear data
     */
    public function clear();

}