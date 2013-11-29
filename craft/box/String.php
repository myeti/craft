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

abstract class String
{

    /**
     * Truncate a string
     * @param $string
     * @param $length
     * @return string
     */
    public static function cut($string, $length)
    {
        return substr($string, 0, $length);
    }


    /**
     * Remove a specified segment in string
     * @param $string
     * @param $segment
     * @return mixed
     */
    public static function remove($string, $segment)
    {
        return str_replace($segment, '', $string);
    }


    /**
     * Check if segment exists in string
     * @param $string
     * @param $segment
     * @return bool
     */
    public static function has($string, $segment)
    {
        return strpos($string, $segment) === false ? false : true;
    }


    /**
     * Extract placeholders from string using regex
     * @param $string
     * @param $pattern
     * @return array|bool
     */
    public static function extract($string, $pattern)
    {
        $success = preg_match($pattern, $string, $out);
        return $success ? $out : false;
    }


    /**
     * Create hash from string
     * @param $string
     * @param string $salt
     * @return string
     */
    public static function hash($string, $salt = '')
    {
        return sha1(uniqid($salt) . $string);
    }

}