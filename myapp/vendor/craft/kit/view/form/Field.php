<?php

namespace craft\kit\view\form;

use craft\kit\view\Template;

abstract class Field
{

    /** string */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $label;

    /** @var string */
    public $helper;

    /** @var string */
    public $value;

    /** @var string */
    public $parent;

    /** @var string */
    public $templates = [];


    /**
     * Set unique field id
     * & setup field data
     */
    public function __construct($name, $value = null, $label = null, $helper = null, $parent = null)
    {
        $this->id = uniqid();
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->helper = $helper;
        $this->parent = $parent;

        $templates = (array)$this->templates();
        $this->templates = array_merge($this->templates, $templates);
    }


    /**
     * Define templates
     * @return array
     */
    abstract public function templates();


    /**
     * Get formatted name
     * @return string
     */
    public function name()
    {
        return $this->parent ? $this->parent . '[' . $this->name . ']' : $this->name;
    }


    /**
     * Render label only
     * @return mixed
     */
    public function label()
    {
        if(!$this->label) return null;
        return Template::forge($this->templates['label'], ['field' => $this]);
    }


    /**
     * Render input only
     * @return string
     */
    public function input()
    {
        return Template::forge($this->templates['input'], ['field' => $this]);
    }


    /**
     * Render helper only
     * @return string
     */
    public function helper()
    {
        if(!$this->label) return null;
        return Template::forge($this->templates['helper'], ['field' => $this]);
    }


    /**
     * Render label, input and helper
     * @return string
     */
    public function html()
    {
        return Template::forge($this->templates['field'], ['field' => $this]);
    }

} 