<?php

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
	 * @param array  $args
	 * @param string  $message
	 */
	public function __construct($name, array $args = [], $message = null)
	{
		$this->name = $name;
		$this->args = $args;
		parent::__construct($message);
	}

}