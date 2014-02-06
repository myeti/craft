<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Cli;

class In
{

    /**
     * Init scanner
     */
    protected static function scanner()
    {
        static $instance;
        if(!$instance) {
            $instance = fopen('php://stdin', 'r');
        }

        return $instance;
    }

    /**
     * Get user input
     * @return string
     */
    public static function read()
    {
        $input = fgets(static::scanner());
        return trim($input);
    }

    /**
     * Ask question and get user answer
     * @param $message
     * @return string
     */
    public static function ask($message)
    {
        Out::say($message);
        return static::read();
    }

}