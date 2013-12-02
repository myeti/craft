<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box;

abstract class FlatArray
{

	/** @var array */
	protected static $_data = [];

	/**
	 * Load external array
	 * @param  array  $data
	 */
	public static function load(array $data)
	{
		static::$_data = $data;
	}

	/**
	 * Find a value using dot syntax
	 * @param  string $name
	 * @return mixed
	 */
	public static function get($name)
	{
		return static::find($name);
	}

	/**
	 * Store a value using dot syntax
	 * @param string $name
	 * @param mixed $value
	 */
	public static function set($name, $value)
	{
		static::find($name, $value);
	}

    /**
     * Remove value using dot syntax
     * @param string $name
     */
	public static function drop($name)
	{
		static::find($name, null);
	}

	/**
	 * Get all data
	 * @return array
	 */
	public static function dump()
	{
		return static::$_data;
	}

    /**
     * Find a sub-value using dot syntax
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
	protected static function find($name, $value = INF)
	{
		// split segments
		$segments = explode('.', $name);

		// walk array
		$cursor = &static::$_data;
		foreach($segments as $segment) {

			// create space
			if(!isset($cursor[$segment])) {
                $cursor[$segment] = null;
			}

			// next segment
			$cursor = &$cursor[$segment];
		}

        // set
        if($value !== INF) {
            $cursor = $value;
        }

        // get
		return $cursor;
	}

}