<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Text;

abstract class Date
{

    /**
     * Get current date
     * @param string $format
     * @return string
     */
    public static function now($format = 'd/m/Y H:i:s')
    {
        return date($format);
    }

}