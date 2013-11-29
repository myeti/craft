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

abstract class Console
{

	/** @var array */
	protected static $commands = [];

	/** @var resource */
	protected static $scanner;


	/**
	 * Register command
	 * @param  string $name
	 * @param  Closure $callback
	 */
	public static function command($command, \Closure $callback)
	{
		// parse
		$exp = explode(' ', $command);
		$name = array_shift($exp);
		$params = [];
		foreach($exp as $param) {
			$state = (substr($param, 0, 1) == '+') ? 'optional' : 'required';
			$param = ltrim('+[', $param);
			$param = rtrim(']', $param);
			$params[$param] = $state;
		}

		// register
		static::$commands[$name] = (object)[
			'rule' => $command,
			'params' => $params,
			'callback' => $callback
		];
	}


	/**
	 * Register lading command
	 * @param  Closure $callback
	 */
	public static function welcome(\Closure $callback)
	{
		static::command(null, $callback);
	}


	/**
	 * Get all commands
	 * @return array
	 */
	public static function commands()
	{
		return static::$command;
	}


	/**
	 * Execute a command
	 * @param  string $command
	 * @param  array  $args
	 */
	protected static function execute($command, array $args)
	{
		// error
		if(!isset(static::$commands[$command])) {
			static::say('Unknown command "' . $command . '"');
			exit;
		}

		// get command details
		$callback = static::$commands[$command]->callback;
		$defs = static::$commands[$command]->params;
		$params = array_combine(array_keys($defs), $args);

		// format params
		foreach($defs as $def => $state) {

			// required
			if($state == 'required' and is_null($params[$def])) {
				static::say('Params "' . $def . '" of command "' . $command . '" is required.');
			}

		}

		// execute
		return call_user_func_array($callback, $params);
	}


	/**
	 * Public alias, forward to another command
	 * @param  string $command
	 * @param  array  $args
	 */
	public static function forward($command, array $args)
	{
		return static::execute($command, $args);
	}


	/**
	 * Write message
	 * @param string|array $msg
	 */
	public static function say($msg = null)
	{
		if(is_array($msg)) {
			foreach($msg as $line) {
				static::say($line);
			}
			return;
		}

		echo $msg, "\n";
	}


	/**
	 * Write on the last line
	 * @param  string $msg
	 */
	public static function replace($msg)
	{
		echo "\r", $msg;
	}


	/**
	 * Wait and read user input
	 * @param  string $question
	 * @return string
	 */
	public static function ask($question)
	{
		// late init
		if(!static::$scanner) {
			static::$scanner = fopen('php://stdin', 'r');
		}

		// display inline message
		echo $question, ' ';

		// get user input
		return trim(fgets(static::$scanner));
	}


	/**
	 * Let's go !
	 */
	public static function plug()
	{
		// get args
		$args = $_SERVER['argv'];
		array_shift($args);

		// get command
		$command = array_shift($args);

		// forward to command execution
		return static::execute($command, $args);
	}

}