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

abstract class Dialog
{

    /** @var resource */
    protected static $input;


    /**
     * Write message
     * @param string $message
     * @return Dialog
     */
    public static function say(...$message)
    {
        if(end($message) === false) {
            array_pop($message);
            echo implode(null, $message);
        }
        else {
            echo implode(null, $message) . "\n";
        }
    }


    /**
     * Write new line
     * @return Dialog
     */
    public static function ln()
    {
        echo "\n";
    }


    /**
     * Get user input
     * @return string
     */
    public static function read()
    {
        if(!static::$input) {
            static::$input = fopen('php://stdin', 'r');
        }

        return trim(fgets(static::$input));
    }


    /**
     * Ask question and get user answer
     * @param $message
     * @return string
     */
    public static function ask(...$message)
    {
        array_push($message, ' ');
        array_push($message, false);
        static::say(...$message);
        return static::read();
    }


    /**
     * Ask yes/no question
     * @param $message
     * @return bool
     */
    public static function confirm(...$message)
    {
        $confirm = static::ask(...$message);
        $confirm = strtolower($confirm);

        // yes
        if($confirm == 'y' or $confirm == 'yes') {
            return true;
        }
        // no
        elseif($confirm == 'n' or $confirm == 'no') {
            return false;
        }

        // unknown answer
        return static::confirm(...$message);
    }

}