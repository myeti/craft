<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\view;

use craft\box\text\String;

class Template extends Sandbox
{

    /**
     * Quick view forging
     * @param $template
     * @param array $vars
     * @return string
     */
    public static function forge($template, array $vars = [])
    {
        $sandbox = new self($template, $vars);
        return (string)$sandbox;
    }

}