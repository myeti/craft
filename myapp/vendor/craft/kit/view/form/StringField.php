<?php

namespace craft\kit\view\form;

use craft\kit\view\Template;

class StringField extends Field
{

    /** @var string */
    public $placeholder;

    /**
     * Render html input
     * @return string
     */
    public function html()
    {
        return Template::forge(__DIR__ . '/templates/string.php', ['field' => $this]);
    }

    /**
     * Render label only
     * @return string
     */
    public function label()
    {
        if(!$this->label) return null;
        return Template::forge(__DIR__ . '/templates/string.label.php', ['field' => $this]);
    }

    /**
     * Render helper only
     * @return string
     */
    public function helper()
    {
        if(!$this->helper) return null;
        return Template::forge(__DIR__ . '/templates/string.helper.php', ['field' => $this]);
    }

    /**
     * Render input only
     * @return string
     */
    public function input()
    {
        return Template::forge(__DIR__ . '/templates/string.input.php', ['field' => $this]);
    }

}