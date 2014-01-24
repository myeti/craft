<?php

namespace craft\kit\view\form;

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


    /**
     * Set unique field id
     */
    public function __construct()
    {
        $this->id = uniqid();
    }

    /**
     * Render label only
     * @return mixed
     */
    abstract public function label();

    /**
     * Render helper only
     * @return string
     */
    abstract public function helper();

    /**
     * Render input only
     * @return string
     */
    abstract public function input();

    /**
     * Render all (label + input + helper)
     * @return string
     */
    abstract public function html();

} 