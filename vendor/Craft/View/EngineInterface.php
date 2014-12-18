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
     * Render content
     * @param mixed $content
     * @param array $data
     * @return string
     */
    public function render($content, $data = null);

} 