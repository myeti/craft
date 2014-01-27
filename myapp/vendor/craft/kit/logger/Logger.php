<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\logger;

abstract class Logger
{

	/** @var array */
	protected static $_logs = [];

	/** @var \SplFileObject */
	protected static $_file;

	/** Log levels */
	const LEVEL_DEFAULT = 'log';
	const LEVEL_INFO = 'info';
	const LEVEL_DEBUG = 'debug';
	const LEVEL_WARNING = 'warning';
	const LEVEL_ERROR = 'error';

	/**
	 * Select logs directory
	 * @param  string $dirname
	 */
	public static function dir($dirname)
	{
		$filename = $dirname . DIRECTORY_SEPARATOR . date('Y-m-d H-i-s') . '.txt';
		static::$_file = new \SplFileObject($filename, 'w+');
	}

	/**
	 * Return all logs
	 * @return array
	 */
	public static function logs()
	{
		return static::$_logs;
	}

	/**
	 * Default log
	 * @param  string $message
	 */
	public static function log($message)
	{
		static::write($message, self::LEVEL_DEFAULT);
	}

	/**
	 * Info log
	 * @param  string $message
	 */
	public static function info($message)
	{
		static::write($message, self::LEVEL_INFO);
	}

	/**
	 * Debug log
	 * @param  string $message
	 */
	public static function debug($message)
	{
		static::write($message, self::LEVEL_DEBUG);
	}

	/**
	 * Warning log
	 * @param  string $message
	 */
	public static function warning($message)
	{
		static::write($message, self::LEVEL_WARNING);
	}

	/**
	 * Error log
	 * @param  string $message
	 */
	public static function error($message)
	{
		static::write($message, self::LEVEL_ERROR);
	}

	/**
	 * Write log
	 * @param  string $message
	 * @param  string $level
	 */
	protected static function write($message, $level)
	{
		// create log
        $log = new Log($level, $message);

		// push log
		static::$_logs[] = $log;

		// write in file
		if(static::$_file instanceof \SplFileObject) {
			static::$_file->fwrite($log . "\n");
		}
	}

}