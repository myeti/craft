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

interface EngineInterface
{

    /**
     * Set views directory
     * @param string $dir
     */
    public function dir($dir);

    /**
     * Render template using data
     * @param string $template
     * @param array $data
     * @return string
     */
    public function render($template, $data = []);

} 