<?php

namespace craft\kit\web;

use craft\kit\view\Template;

trait Gear
{

    /**
     * Perform action
     * @return array
     */
    abstract protected function perform();

    /**
     * Return template to use
     * @return string
     */
    abstract protected function template();

    /**
     * Render template
     * @return string
     */
    public function render()
    {
        $data = (array)$this->perform();
        $template = (string)$this->template();

        return Template::forge($template, $data);
    }

} 