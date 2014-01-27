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
use craft\box\storage\File;
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
        $template = String::ensure($template, '.php');

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
    protected function layout($resource)
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


    /**
     * Meta markup
     * @return string
     */
    protected static function meta()
    {
        $meta = "\n\t" . '<meta charset="UTF-8">';
        $meta .= "\n\t" . '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1" />';

        return $meta . "\n";
    }


    /**
     * CSS markup
     * @return string
     */
    protected static function css()
    {
        $str = '';
        foreach(func_get_args() as $file) {
            $file = String::ensure($file, '.css');
            $str .= "\n\t" . '<link type="text/css" media="screen" href="' . url($file) . '" rel="stylesheet" />';
        }

        return $str . "\n";
    }


    /**
     * JS markup
     * @return string
     */
    protected static function js()
    {
        $str = '';
        foreach(func_get_args() as $file) {
            $file = String::ensure($file, '.js');
            $str .= "\n\t" . '<script type="text/javascript" src="' . url($file) . '"></script>';
        }

        return $str . "\n";
    }

}