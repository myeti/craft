<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box\cli;

class Input
{

    /** @var resource */
    protected static $_scanner;

    /**
     * Init scanner
     */
    protected static function init()
    {
        if(!static::$_scanner) {
            static::$_scanner = fopen('php://stdin', 'r');
        }
    }

    /**
     * Get user input
     * @return string
     */
    public static function read()
    {
        static::init();
        return trim(fgets(static::$_scanner));
    }

    /**
     * Ask question and get user answer
     * @param $message
     * @return string
     */
    public static function ask($message)
    {
        static::init();
        Output::write($message);
        return static::read();
    }

} 