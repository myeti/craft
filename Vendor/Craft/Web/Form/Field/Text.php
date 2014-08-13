<?php

namespace Craft\Web\Form\Field;

use Craft\View\Engine;
use Craft\Web\Form\Field;

class Text extends Field
{

    /**
     * Render input
     * @return string
     */
    public function input()
    {
        return Engine::make(dirname(__DIR__) . '/templates/text.input', ['field' => $this]);
    }

} 