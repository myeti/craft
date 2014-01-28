<?php

namespace craft\kit\view\form;

use craft\kit\view\Template;

class TextField extends StringField
{

    /**
     * Render input only
     * @return string
     */
    public function input()
    {
        return Template::forge(__DIR__ . '/templates/text.input.php', ['field' => $this]);
    }

}