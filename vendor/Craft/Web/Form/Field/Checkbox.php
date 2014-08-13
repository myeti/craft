<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Web\Form\Field;

use Craft\View\Engine;
use Craft\Web\Form\Field;

class Checkbox extends Field
{

    /**
     * Render input
     * @return string
     */
    public function input()
    {
        return Engine::make(dirname(__DIR__) . '/templates/checkbox.input', ['field' => $this]);
    }

} 