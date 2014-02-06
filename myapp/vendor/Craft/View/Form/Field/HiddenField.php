<?php

namespace Craft\View\Form\Field;

use Craft\View\Form\Field;

class HiddenField extends Field
{

    /**
     * Render label only
     * @return mixed
     */
    public function label() {}

    /**
     * Render input only
     * @return string
     */
    public function input()
    {
        return $this->render(__DIR__ . '/../templates/hidden.input');
    }

    /**
     * Render helper only
     * @return string
     */
    public function helper() {}

}