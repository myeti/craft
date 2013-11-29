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
		$data = static::find($name, true);
		$data = $value;
	}

	/**
	 * Remove value using dot syntax
	 * @param  [type] $name [description]
	 * @return [type]       [description]
	 */
	public static function drop($name)
	{
		$data = static::find($name, true);
		unset($data);
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
	 * @param  string  $name
	 * @param  boolean $silent continue when not found
	 * @return mixed
	 */
	protected static function find($name, $silent = false)
	{
		// split segments
		$segments = explode('.', $name);

		// walk array
		$cursor = &static::$_data;
		foreach($segments as $segment) {

			// create space
			if(!isset($cursor[$segment])) {

				if($silent) {
					$cursor[$segment] = [];
				}
				else {
					break;
				}

			}

			// next segment
			$cursor = &$cursor[$segment];
		}

		return $cursor;
	}

}