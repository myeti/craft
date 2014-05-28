<?php

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
        return Engine::forge(dirname(__DIR__) . '/templates/checkbox.input', ['field' => $this]);
    }

} 