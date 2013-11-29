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

abstract class Session extends FlatArray
{

	/**
	 * Start session
	 */
	public static function init()
	{
		if(!isset($_SESSION['craft.session'])) {
			$_SESSION['craft.session'] = [];
		}

		static::$_data = &$_SESSION['craft.session'];
	}

}