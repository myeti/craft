<?php

namespace Craft\App\View\Form;

use Craft\Box\Data\SilentArray;
use Craft\App\View;

abstract class Field extends SilentArray
{

    /**
     * Set unique field id
     * & setup field data
     */
    public function __construct($name, array $config = [])
    {
        // default config
        $config = $config + [
            'id'        => $name. '_' . uniqid(),
            'name'      => $name,
            'value'     => null,
            'label'     => null,
            'helper'    => null,
            'parent'    => null
        ];

        parent::__construct($config);
    }


    /**
     * Get formatted name
     * @return string
     */
    public function name()
    {
        return $this['parent'] ? $this['parent'] . '[' . $this['name'] . ']' : $this['name'];
    }


    /**
     * Render template
     * @param $template
     * @param array $data
     * @return string
     */
    protected function render($template, array $data = [])
    {
        return View::forge($template, $data + ['field' => $this]);
    }


    /**
     * Render label only
     * @return mixed
     */
    public function label()
    {
        return $this->render(__DIR__ . '/templates/field.label');
    }


    /**
     * Render input only
     * @return string
     */
    abstract public function input();


    /**
     * Render helper only
     * @return string
     */
    public function helper()
    {
        return $this->render(__DIR__ . '/templates/field.helper');
    }


    /**
     * Render label, input and helper
     * @return string
     */
    /**
     * Render label, input and helper
     * @return string
     */
    public function html()
    {
        return $this->render(__DIR__ . '/templates/field');
    }

} 