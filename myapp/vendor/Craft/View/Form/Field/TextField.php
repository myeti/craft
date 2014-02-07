<?php

namespace Craft\View\Form\Field;

use Craft\View\Form\Field;

class TextField extends Field
{

    /**
     * Render input only
     * @return string
     */
    public function input()
    {
        return $this->render('text.input');
    }

}