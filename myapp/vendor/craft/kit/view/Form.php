<?php

namespace craft\kit\view;

use craft\box\meta\Annotation;
use craft\kit\view\form\StringField;
use craft\kit\view\form\TextField;

class Form extends \ArrayObject
{

    /** @var string */
    public $action;

    /**
     * Load entity
     * @param object $entity
     * @param string $action
     */
    public function __construct($entity, $action = '#')
    {
        // set action
        $this->action = $action;

        // parse properties
        foreach($entity as $prop => $value) {

            // get type
            $type = Annotation::property($entity, $prop, 'var');

            // create field
            if($type == 'string text') {
                $field = new TextField($prop, $value);
            }
            else {
                $field = new StringField($prop, $value);
            }

            // bind to form
            $this[$prop] = $field;

        }
    }


    /**
     * Render html form
     */
    public function html()
    {
        return Template::forge(__DIR__ . '/templates/form.php', ['form' => $this]);
    }

} 