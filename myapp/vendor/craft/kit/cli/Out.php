<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\cli;

abstract class Out
{

    /**
     * Write message and skip line
     * @param $message
     */
    public static function say($message)
    {
        static::write($message);
    }


    /**
     * Skip a line
     */
    public static function jump()
    {
        static::write(null, true);
    }


    /**
     * Write one the same line
     * @param $message
     */
    public static function refresh($message)
    {
        static::write("\r" . $message);
    }


    /**
     * Write message
     * @param $message
     * @param bool $break
     */
    protected static function write($message, $break = false)
    {
        // many lines
        if(is_array($message)) {
            foreach($message as $line) {
                static::write($line, $break);
            }
            return;
        }

        // display message
        echo $message;

        // new line
        if($break) {
            echo "\n";
        }
    }

}