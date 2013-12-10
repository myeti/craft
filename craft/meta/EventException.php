<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\meta;

class EventException extends \Exception
{

	/** @var string */
	public $name;

	/** @var array */
	public $args = [];

	/**
	 * Init with event name
	 * @param string  $name
	 * @param string  $message
	 * @param array  $args
	 */
	public function __construct($name, $message = null, array $args = [])
	{
		$this->name = $name;
		$this->args = $args;
		parent::__construct($message);
	}

}