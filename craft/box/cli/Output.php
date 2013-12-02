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

class Output
{

    /**
     * Write message
     * @param $message
     * @param bool $break
     */
    public static function write($message, $break = false)
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

    /**
     * Skip a line
     */
    public static function skip()
    {
        static::write(null, true);
    }

    /**
     * Write message and skip line
     * @param $message
     */
    public static function line($message)
    {
        static::write($message, true);
    }

    /**
     * Alias of line()
     * @param $message
     */
    public static function say($message)
    {
        static::line($message);
    }

    /**
     * Write one the same line
     * @param $message
     */
    public static function refresh($message)
    {
        static::write("\r" . $message);
    }

} 