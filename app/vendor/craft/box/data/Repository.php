<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box\data;

use craft\box\data\provider\Provider;

class Repository extends \ArrayObject implements Provider
{

    /**
     * Create repository from array and/or base key
     * @param array $array
     * @param null $key
     */
    public function __construct(array &$array = [], $key = null)
    {
        // has base key
        if($key) {

            // create base key
            if(!isset($array[$key])) {
                $array[$key] = [];
            }

            $array = $array[$key];
        }

        parent::__construct($array);
    }


    /**
     * Check if a dot key exists
     * @param $key
     * @return bool
     */
	public function has($key)
	{
        // init
        $array = $this;
        $keys = explode('.', $key);

        // walk to the end
        while(count($keys) > 1) {

            // current segment
            $key = array_shift($keys);

            // has not
            if(!isset($array[$key]) or !is_array($array[$key])) {
                return false;
            }

            // next
            $array = $array[$key];
        }

        // has ?
        $key = array_shift($keys);
        if(!isset($array[$key])) {
            return false;
        }

        return true;
	}


    /**
     * Retrieve a value using dot syntax
     * @param $key
     * @param null $fallback
     * @return mixed
     */
	public function get($key, $fallback = null)
	{
        // init
        $array = $this;
        $keys = explode('.', $key);

        // walk to the end
        while(count($keys) > 1) {

            // current segment
            $key = array_shift($keys);

            // undefined cursor, break
            if(!isset($array[$key]) or !is_array($array[$key])) {
                return $fallback;
            }

            // next
            $array = $array[$key];
        }

        // get
        $key = array_shift($keys);
        return $array[$key];
	}


    /**
     * Store a value using dot syntax
     * @param $key
     * @param mixed $value
     * @return $this
     */
	public function set($key, $value)
	{
        // init
        $array = &$this;
        $keys = explode('.', $key);

        // walk to the end
        while(count($keys) > 1) {

            // current segment
            $key = array_shift($keys);

            // undefined cursor, break
            if(!isset($array[$key])) {
                $array[$key] = null;
            }

            // next
            $array = &$array[$key];
        }

        // set
        $key = array_shift($keys);
        $array[$key] = $value;

        return $this;
	}


    /**
     * Push a value using dot syntax
     * @param $key
     * @param mixed $value
     * @return $this
     */
    public function push($key, $value)
    {
        // init
        $array = &$this;
        $keys = explode('.', $key);

        // walk to the end
        while(count($keys) > 1) {

            // current segment
            $key = array_shift($keys);

            // undefined cursor, break
            if(!isset($array[$key])) {
                $array[$key] = null;
            }

            // next
            $array = &$array[$key];
        }

        // force array
        $key = array_shift($keys);
        if(!is_array($array[$key])) {
            $array[$key] = [];
        }

        // push
        $array[$key][] = $value;

        return $this;
    }


    /**
     * Remove value using dot syntax
     * @param $key
     * @return $this
     */
	public function drop($key)
	{
        // init
        $array = &$this;
        $keys = explode('.', $key);

        // walk to the end
        while(count($keys) > 1) {

            // current segment
            $key = array_shift($keys);

            // undefined cursor, break
            if(!isset($array[$key]) or !is_array($array[$key])) {
                return $this;
            }

            // next
            $array = &$array[$key];
        }

        // remove
        $key = array_shift($keys);
        unset($array[$key]);

		return $this;
	}


    /**
     * Retrieve value using dot syntax and drop key
     * @param $key
     * @return mixed
     */
    public function pull($key)
    {
        $value = $this->get($key);
        $this->drop($key);

        return $value;
    }

}