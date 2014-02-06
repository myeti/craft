<?php

namespace Craft\View\Form;

use Craft\View\Form;

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
        $this->template = __DIR__ . '/templates/field.inner';

        parent::__construct($entity);

        foreach($this as $field => $object) {
            $this[$field]->parent = $name;
        }
    }

} 