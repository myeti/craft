<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\data;

use craft\box\FlatArray;

abstract class Flash extends FlatArray
{

	/**
	 * Start flash
	 */
	public static function init()
	{
		if(!isset($_SESSION['craft.flash'])) {
			$_SESSION['craft.flash'] = [];
		}

		static::$_data = &$_SESSION['craft.flash'];
	}

	/**
	 * Remove message after reading
	 * @param  string $name
	 * @return mixed
	 */
	public static function get($name)
	{
		$value = parent::get($name);
		static::drop($name);
		return $value;
	}

}