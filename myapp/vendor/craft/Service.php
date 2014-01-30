<?php

namespace Craft;

use Craft\Service\Loader;
use Craft\Service\Tracker;
use Psr\Log\LoggerInterface;

abstract class Service
{

    /** @var Loader */
    protected static $loader;

    /** @var LoggerInterface */
    protected static $logger;

    /** @var Tracker */
    protected static $tracker;


    /**
     * Globalize loader
     * @param Loader $loader
     * @return Loader
     */
    public static function loader(Loader &$loader = null)
    {
        if($loader) {
            static::$loader = $loader;
        }

        return static::$loader;
    }


    /**
     * Globalize logger
     * @param LoggerInterface $logger
     * @return LoggerInterface
     */
    public static function logger(LoggerInterface &$logger = null)
    {
        if($logger) {
            static::$logger = $logger;
        }

        return static::$logger;
    }


    /**
     * Globalize tracker
     * @param Tracker $tracker
     * @return Tracker
     */
    public static function tracker(Tracker &$tracker = null)
    {
        if($tracker) {
            static::$tracker = $tracker;
        }

        return static::$tracker;
    }

} 