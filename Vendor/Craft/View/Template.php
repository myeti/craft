<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\View;

class Template extends Sandbox
{

    /**
     * Quick template forging
     * @param Engine $engine
     * @param string $template
     * @param array $data
     * @param array $sections
     * @return string
     */
    public static function forge(Engine $engine, $template, array $data = [], array $sections = [])
    {
        $sandbox = new self($engine);
        return $sandbox->render($template, $data, $sections);
    }

} 