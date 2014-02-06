<?php

namespace Craft\View\Form\Field;

use Craft\View\Form\Field;

class EmailField extends Field
{

    /**
     * Render input only
     * @return string
     */
    public function input()
    {
        return $this->render(__DIR__ . '/../templates/email.input');
    }

}