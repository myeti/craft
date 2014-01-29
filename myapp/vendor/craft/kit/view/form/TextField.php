<?php

namespace craft\kit\view\form;

class TextField extends StringField
{

    /**
     * Define templates
     * @return array
     */
    public function templates()
    {
        return [
            'input' => __DIR__ . '/templates/text.input'
        ] + parent::templates();
    }

}