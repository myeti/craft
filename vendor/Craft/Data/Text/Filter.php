<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Data\Text;

class Filter
{

    /**
     * Is email
     * @param $string
     * @return mixed
     */
    public static function email($string)
    {
        return filter_var($string, FILTER_VALIDATE_EMAIL);
    }


    /**
     * Is url
     * @param $string
     * @return mixed
     */
    public static function url($string)
    {
        return filter_var($string, FILTER_VALIDATE_URL);
    }


    /**
     * Is ip address
     * @param $string
     * @return mixed
     */
    public static function ip($string)
    {
        return filter_var($string, FILTER_VALIDATE_IP);
    }


    /**
     * Is regex
     * @param $string
     * @return mixed
     */
    public static function regex($string)
    {
        return filter_var($string, FILTER_VALIDATE_REGEXP);
    }


    /**
     * Is regex
     * @param $string
     * @return mixed
     */
    public static function serialized($string)
    {
        $decoded = @unserialize($string);
        return ($decoded !== false || $string == 'b:0;');
    }


    /**
     * Check range
     * @param $int
     * @param $min
     * @param $max
     * @return bool
     */
    public static function between($int, $min, $max)
    {
        return filter_var($int, FILTER_VALIDATE_INT, [
            'options' => [
                'min_range' => $min,
                'max_range' => $max,
            ]
        ]);
    }

} 