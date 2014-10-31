<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Debug;

use Psr\Log\LoggerInterface;

abstract class Logger
{

    /** @var LoggerInterface[] */
    protected static $instances = [];


    /**
     * Register logger
     * @param LoggerInterface $logger
     */
    public static function register(LoggerInterface $logger)
    {
        static::$instances[] = $logger;
    }


    /**
     * Clear loggers
     */
    public static function clear()
    {
        static::$instances[] = [];
    }


    /**
     * System is unusable.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function emergency($message, array $context = [])
    {
        foreach(static::$instances as $logger) {
            $logger->emergency($message, $context);
        }
    }


    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function alert($message, array $context = [])
    {
        foreach(static::$instances as $logger) {
            $logger->alert($message, $context);
        }
    }


    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function critical($message, array $context = [])
    {
        foreach(static::$instances as $logger) {
            $logger->critical($message, $context);
        }
    }


    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function error($message, array $context = [])
    {
        foreach(static::$instances as $logger) {
            $logger->error($message, $context);
        }
    }


    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function warning($message, array $context = [])
    {
        foreach(static::$instances as $logger) {
            $logger->warning($message, $context);
        }
    }


    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function notice($message, array $context = [])
    {
        foreach(static::$instances as $logger) {
            $logger->notice($message, $context);
        }
    }


    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function info($message, array $context = [])
    {
        foreach(static::$instances as $logger) {
            $logger->info($message, $context);
        }
    }


    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array $context
     * @return null
     */
    public static function debug($message, array $context = [])
    {
        foreach(static::$instances as $logger) {
            $logger->debug($message, $context);
        }
    }


    /**
     * Write log
     *
     * @param int $level
     * @param string $message
     * @param array $context
     */
    public static function log($level, $message, array $context = [])
    {
        foreach(static::$instances as $logger) {
            $logger->log($level, $message, $context);
        }
    }

}