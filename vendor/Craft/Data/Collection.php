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

class Collection extends Provider
{

    /**
     * Get first element
     * @return mixed
     */
    public function first()
    {
        return Set::first($this);
    }


    /**
     * Get first key
     * 
     * @return string
     */
    public function firstKey()
    {
        return Set::firstKey($this);
    }


    /**
     * Get last element
     * 
     * @return mixed
     */
    public function last()
    {
        return Set::last($this);
    }


    /**
     * Get last key
     * 
     * @return string
     */
    public function lastKey()
    {
        return Set::lastKey($this);
    }


    /**
     * Find first key of matched value
     * 
     * @param mixed $value
     * @return int
     */
    public function keyOf($value)
    {
       return Set::keyOf($this, $value);
    }


    /**
     * Find all keys of matched value
     * 
     * @param mixed $value
     * @return array
     */
    public function keysOf($value)
    {
        return Set::keysOf($this, $value);
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
        $this->exchangeArray(Set::replace($this, $value, $replacement));
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
        $this->exchangeArray(Set::replaceKey($this, $key, $replacement));
        return $this;
    }


    /**
     * Get keys
     * 
     * @return array
     */
    public function keys()
    {
        return Set::keys($this);
    }


    /**
     * Get values
     * 
     * @return array
     */
    public function values()
    {
        return Set::values($this);
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
        $this->exchangeArray(Set::insert($this, $value, $at));
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
        $this->exchangeArray(Set::filter($this, $callback));
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
        $this->exchangeArray(Set::filterKey($this, $callback));
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
        return Set::random($this, $num);
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
        $this->exchangeArray(Set::sort($this, $by));
        return $this;
    }

}