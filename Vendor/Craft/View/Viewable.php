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

interface Viewable
{

    /**
     * Render view
     * @param mixed $something
     * @return string
     */
    public function render($something);

} 