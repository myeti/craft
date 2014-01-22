<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\view;

use craft\box\error\FileNotFoundException;
use craft\box\text\String;

abstract class Sandbox
{

    /** @var string */
    private $_extend;

    /** @var string */
    private $_template;

    /** @var array */
    private $_data = [];

    /** @var array */
    private $_slots = [];


    /**
     * Setup and compile
     * @param $template
     * @param array $data
     * @param array $slots
     * @throws FileNotFoundException
     */
    public function __construct($template, array $data = [], array $slots = [])
    {
        // php view
        $template = String::rtrim($template, '.php') . '.php';

        // setup sandbox
        $this->_template = $template;
        $this->_data = $data;
        $this->_slots = $slots;
    }


    /**
     * Compile view
     * @return string
     */
    public function __toString()
    {
        // compile
        ob_start();
        extract($this->_data);
        require $this->_template;
        $content = ob_get_clean();

        // has layout ?
        if($this->_extend) {
            $class = get_called_class();
            $slots = array_merge($this->_slots, ['__content' => $content]);
            $content = new $class($this->_extend, $this->_data, $slots);
        }

        return (string)$content;
    }


    /**
     * Set upper layout
     * @param $resource
     */
    protected function extend($resource)
    {
        $this->_extend = $resource;
    }


    /**
     * Fill slot
     * @param $name
     * @param $content
     */
    protected function slot($name, $content)
    {
        $this->_slots[$name] = $content;
    }


    /**
     * Get slot
     * @param $name
     * @return mixed
     */
    protected function hook($name)
    {
        return isset($this->_slots[$name]) ? $this->_slots[$name] : null;
    }


    /**
     * Alias hook(__content)
     * @return mixed
     */
    protected function content()
    {
        return $this->hook('__content');
    }

}