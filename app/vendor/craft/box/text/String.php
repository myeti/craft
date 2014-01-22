<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box\text;

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
     * Cut and add ellipsis to string
     * @param $string
     * @param $length
     * @return string
     */
    public static function ellipsis($string, $length)
    {
        // big enough
        if($length - 3 > 0) {
            $length -= 3;
        }

        return static::cut($string, $length) . '...';
    }


    /**
     * Divide text depending on length
     * @param $string
     * @param $length
     * @param string $break
     * @return string
     */
    public static function split($string, $length, $break = '<br/>')
    {
        return wordwrap($string, $length, $break, true);
    }


    /**
     * Remove a specified segment in string
     * @param $string
     * @param string|array $segment
     * @return mixed
     */
    public static function remove($string, $segment)
    {
        // multi removing
        if(is_array($segment)) {
            foreach($segment as $substring) {
                $string = static::remove($string, $substring);
            }

            return $string;
        }

        return str_replace($segment, '', $string);
    }


    /**
     * Replace substring
     * @param $string
     * @param $search
     * @param $replacement
     * @return string
     */
    public static function replace($string, $search, $replacement)
    {
        return str_replace($search, $replacement, $string);
    }


    /**
     * Check if segment exists in string
     * @param $string
     * @param $segment
     * @param bool $case_sensitive
     * @return bool
     */
    public static function has($string, $segment, $case_sensitive = true)
    {
        if(!$case_sensitive) {
            $string = strtolower($string);
            $segment = strtolower($segment);
        }

        return strpos($string, $segment) === false ? false : true;
    }


    /**
     * Check if string starts with segment
     * @param $string
     * @param $segment
     * @param bool $case_sensitive
     * @return bool
     */
    public static function lhas($string, $segment, $case_sensitive = true)
    {
        if(!$case_sensitive) {
            $string = strtolower($string);
            $segment = strtolower($segment);
        }

        return substr($string, 0, strlen($segment)) == $segment;
    }


    /**
     * Check if string ends with segment
     * @param $string
     * @param $segment
     * @param bool $case_sensitive
     * @return bool
     */
    public static function rhas($string, $segment, $case_sensitive = true)
    {
        if(!$case_sensitive) {
            $string = strtolower($string);
            $segment = strtolower($segment);
        }

        return substr($string, -strlen($segment)) == $segment;
    }


    /**
     * Create hash from string
     * @param $string
     * @param string $salt
     * @return string
     */
    public static function hash($string, $salt = '')
    {
        return sha1(sha1($salt) . $string);
    }


    /**
     * Remove segment on both left and right
     * @param $string
     * @param $segment
     * @param bool $case_sensitive
     * @return string
     */
    public static function trim($string, $segment, $case_sensitive = true)
    {
        if(!$case_sensitive) {
            $string = strtolower($string);
            $segment = strtolower($segment);
        }

        $string = static::ltrim($string, $segment);
        return static::rtrim($string, $segment);
    }


    /**
     * Remove segment on left
     * @param $string
     * @param $segment
     * @param bool $case_sensitive
     * @return string
     */
    public static function ltrim($string, $segment, $case_sensitive = true)
    {
        if(!$case_sensitive) {
            $string = strtolower($string);
            $segment = strtolower($segment);
        }

        if(static::lhas($string, $segment)) {
            $string = substr($string, strlen($segment));
        }

        return $string;
    }


    /**
     * Remove segment on right
     * @param $string
     * @param $segment
     * @param bool $case_sensitive
     * @return string
     */
    public static function rtrim($string, $segment, $case_sensitive = true)
    {
        if(!$case_sensitive) {
            $string = strtolower($string);
            $segment = strtolower($segment);
        }

        if(static::rhas($string, $segment)) {
            $string = substr($string, 0, -strlen($segment));
        }

        return $string;
    }


    /**
     * Get string length
     * @param $string
     * @return int
     */
    public static function size($string)
    {
        return strlen($string);
    }


    /**
     * Generate string from placeholder env
     * @param $string
     * @param array $vars
     * @return mixed
     */
    public static function compose($string, array $vars = [])
    {
        foreach($vars as $placeholder => $value) {
            $string = str_replace(':' . $placeholder, $value, $string);
        }

        return $string;
    }


    /**
     * Extract env from placeholder in mask
     * @param $string
     * @param $mask
     * @return array|bool
     */
    public static function mask($string, $mask)
    {
        // create pattern
        $pattern = '/^' . preg_replace('/:([\w]+)/', '(?<$1>.+)', $mask) . '$/';
        return Regex::extract($string, $pattern);
    }


    /**
     * Strip accents in string
     * @param $string
     * @param string $encoding
     * @return mixed
     */
    public static function stripAccents($string, $encoding = 'utf-8')
    {
        $accents = [
            'àáâãäå'    => 'a',
            'ç'         => 'c',
            'ð'         => 'd',
            'èéêë'      => 'e',
            'ìíîï'      => 'i',
            'ñ'         => 'n',
            'òóôõöø'    => 'o',
            'š'         => 's',
            'ùúûü'      => 'u',
            'ýÿ'        => 'y',
            'ž'         => 'z',
            'æ'         => 'ae',
            'œ'         => 'oe'
        ];

        foreach($accents as $pattern => $replacement) {
            $string = Regex::replace($string, '/[' . $pattern . ']/', $replacement);
        }

        return $string;
    }

}