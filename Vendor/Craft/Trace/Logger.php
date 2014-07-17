<?php

namespace Craft\Trace;

use Psr\Log\LogLevel;

abstract class Logger
{

    /**
     * Get tracker instance
     * @param TrackerInterface $tracker
     * @return TrackerInterface
     */
    public static function tracker(TrackerInterface $tracker = null)
    {
        static $instance;
        if($tracker) {
            $instance = $tracker;
        }
        if(!$instance) {
            $instance = new Tracker;
        }

        return $instance;
    }


    /**
     * Get all logs
     * @return Log[]
     */
    public static function logs()
    {
        return static::tracker()->logs();
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
        static::log(LogLevel::EMERGENCY, $message, $context);
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
        static::log(LogLevel::ALERT, $message, $context);
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
        static::log(LogLevel::CRITICAL, $message, $context);
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
        static::log(LogLevel::ERROR, $message, $context);
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
        static::log(LogLevel::WARNING, $message, $context);
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
        static::log(LogLevel::NOTICE, $message, $context);
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
        static::log(LogLevel::INFO, $message, $context);
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
        static::log(LogLevel::DEBUG, $message, $context);
    }


    /**
     * Write log
     *
     * @param int $level
     * @param string $message
     * @param array $context
     */
    protected static function log($level, $message, array $context = [])
    {
        static::tracker()->log($level, $message, $context);
    }

} 