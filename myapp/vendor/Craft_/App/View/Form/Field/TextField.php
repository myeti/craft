<?php

namespace Craft\App\View\Form\Field;

use Craft\App\View\Form\Field;

class TextField extends Field
{

    /**
     * Render input only
     * @return string
     */
    public function input()
    {
        return $this->render(__DIR__ . '/../templates/text.input');
    }

}