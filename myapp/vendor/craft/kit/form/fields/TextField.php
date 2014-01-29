<?php

namespace craft\kit\form\fields;

use craft\kit\form\Field;

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