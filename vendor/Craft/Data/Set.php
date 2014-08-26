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

class Set extends Provider
{

    /**
     * Get first element
     * @return mixed
     */
    public function first()
    {
        return array_first($this);
    }


    /**
     * Get first key
     * 
     * @return string
     */
    public function firstKey()
    {
        return array_first_key($this);
    }


    /**
     * Get last element
     * 
     * @return mixed
     */
    public function last()
    {
        return array_last($this);
    }


    /**
     * Get last key
     * 
     * @return string
     */
    public function lastKey()
    {
        return array_last_key($this);
    }


    /**
     * Find first key of matched value
     * 
     * @param mixed $value
     * @return int
     */
    public function keyOf($value)
    {
       return array_key($this, $value);
    }


    /**
     * Find all keys of matched value
     * 
     * @param mixed $value
     * @return array
     */
    public function keysOf($value)
    {
        return array_keys($this, $value);
    }


    /**
     * Replace all value
     * 
     * @param mixed $value
     * @param mixed $replacement
     * @return $this
     */
    public function replace($value, $replacement)
    {
        $this->exchangeArray(array_replace_value($this, $value, $replacement));
        return $this;
    }


    /**
     * Replace key and keep order
     * 
     * @param mixed $key
     * @param mixed $replacement
     * @return $this
     */
    public function replaceKey($key, $replacement)
    {
        $this->exchangeArray(array_replace_key($this, $key, $replacement));
        return $this;
    }


    /**
     * Get keys
     * 
     * @return array
     */
    public function keys()
    {
        return array_keys($this);
    }


    /**
     * Get values
     * 
     * @return array
     */
    public function values()
    {
        return array_values($this);
    }


    /**
     * Insert element at specific position
     * 
     * @param mixed $value
     * @param string $at
     * @return $this
     */
    public function insert($value, $at)
    {
        $this->exchangeArray(array_insert($this, $value, $at));
        return $this;
    }


    /**
     * Filter values
     * 
     * @param callable $callback
     * @return $this
     */
    public function filter(callable $callback)
    {
        $this->exchangeArray(array_filter($this, $callback));
        return $this;
    }


    /**
     * Filter keys
     * 
     * @param callable $callback
     * @return $this
     */
    public function filterKey(callable $callback)
    {
        $this->exchangeArray(array_filter_key($this, $callback));
        return $this;
    }


    /**
     * Get random element(s)
     * 
     * @param int $num
     * @return mixed|array
     */
    public function random($num = 1)
    {
        return array_random($this, $num);
    }


    /**
     * Count elements
     * @return int
     */
    public function length()
    {
        return count($this);
    }


    /**
     * Sort array by columns
     * - [column => SORT_ASC] let you decide
     * - [column1, column2] will sort ASC
     * 
     * @param array $by
     * @return $this
     */
    public function sort(array $by)
    {
        $this->exchangeArray(array_sort($this, $by));
        return $this;
    }

}