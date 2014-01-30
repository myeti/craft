<?php

namespace Craft\App\View\Form\Field;

use Craft\App\View\Form\Field;

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