<?php

namespace craft\kit\view;

use craft\box\meta\Annotation;
use craft\kit\view\form\StringField;
use craft\kit\view\form\TextField;

class InnerForm extends Form
{

    /** @var string */
    public $name;


    /**
     * Load entity
     * @param null|object $name
     * @param object $entity
     */
    public function __construct($name, $entity = null)
    {
        $this->name = $name;
        $this->template = __DIR__ . '/templates/form.inner';

        parent::__construct($entity);

        foreach($this as $field => $object) {
            $this[$field]->parent = $name;
        }
    }

} 