<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\view\helper;

use craft\box\text\String;

class Html
{

    /**
     * Meta markup
     * @return string
     */
    public static function meta()
    {
        $meta = "\n\t" . '<meta charset="UTF-8">';
        $meta .= "\n\t" . '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1" />';

        return $meta . "\n";
    }


    /**
     * CSS markup
     * @return string
     */
    public static function css()
    {
        $str = '';
        foreach(func_get_args() as $file) {
            $file = String::rtrim($file, '.css');
            $str .= "\n\t" . '<link type="text/css" media="screen" href="' . static::asset($file . '.css') . '" rel="stylesheet" />';
        }

        return $str . "\n";
    }


    /**
     * JS markup
     * @return string
     */
    public static function js()
    {
        $str = '';
        foreach(func_get_args() as $file) {
            $file = String::rtrim($file, '.js');
            $str .= "\n\t" . '<script type="text/javascript" src="' . static::asset($file . '.js') . '"></script>';
        }

        return $str . "\n";
    }


    /**
     * Asset public file
     * @param $filename
     * @return string
     */
    public static function asset($filename)
    {
        return url() . $filename;
    }

} 