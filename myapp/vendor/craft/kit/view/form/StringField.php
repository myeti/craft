<?php

namespace craft\kit\view\form;

class StringField extends Field
{

    /** @var string */
    public $placeholder;

    /**
     * Define templates
     * @return array
     */
    public function templates()
    {
        return [
            'label'     => __DIR__ . '/templates/string.label',
            'input'     => __DIR__ . '/templates/string.input',
            'helper'    => __DIR__ . '/templates/string.helper',
            'field'     => __DIR__ . '/templates/string',
        ];
    }

}