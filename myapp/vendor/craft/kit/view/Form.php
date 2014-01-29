<?php

namespace craft\kit\view;

use craft\box\data\Repository;
use craft\box\meta\Annotation;
use craft\kit\view\form\Field;
use craft\kit\web\Gear;

class Form extends \ArrayObject
{

    /** @var array */
    public $types = [
        'string'    => 'craft\kit\view\form\StringField',
        'text'      => 'craft\kit\view\form\TextField',
        'email'     => 'craft\kit\view\form\EmailField',
        'date'      => 'craft\kit\view\form\DateField',
        'datetime'  => 'craft\kit\view\form\DateTimeField',
        'default'   => 'craft\kit\view\form\StringField',
    ];

    /** @var array */
    public $config = [];


    /**
     * Load entity
     * @param object $entity
     * @param array $override
     */
    public function __construct($entity = null, array $override = [])
    {
        // default config
        $this->config = $override + [
            'form.url'      => '#',
            'form.method'   => 'post',
            'form.template' => __DIR__ . '/form/templates/form'
        ];

        // parse properties
        if($entity and is_object($entity)) {
            foreach($entity as $prop => $value) {

                // set type
                $type = isset($this->config[$prop . '.type'])
                    ? $this->config[$prop . '.type']
                    : Annotation::property($entity, $prop, 'var');

                // create field
                $fieldType = isset($this->types[$type]) ? $this->types[$type] : $this->types['default'];
                $field = new $fieldType($prop, $value);

                // override
                foreach(['name', 'value', 'label', 'placeholder'] as $item) {
                    if(isset($this->config[$prop . '.value'])) {
                        $field->{$item} = $this->config[$prop . '.value'];
                    }
                }

                // bind to form
                $this[$prop] = $field;

            }
        }

    }


    /**
     * Return template to use
     * @return string
     */
    public function html()
    {
        return Template::forge($this->template, ['form' => $this]);
    }

}