<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\View;

class Engine implements Renderer
{

    /** @var string */
    public $templates;

    /** @var string */
    protected $ext = '.php';

    /** @var callable[] */
    protected $helpers = [];

    /** @var Engine[] */
    protected static $instances = [];


    /**
     * Setup engine
     * @param string $directory
     * @param string $ext
     */
    public function __construct($directory, $ext = '.php')
    {
        // template directory
        $this->templates = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        // file extension
        $this->ext = $ext;

        // inner rendering
        $this->helper('partial', [$this, 'render']);
    }


    /**
     * Set helper function
     * @param string $fn
     * @param callable $callback
     * @return $this
     */
    public function helper($fn, callable $callback)
    {
        $this->helpers[$fn] = $callback;

        return $this;
    }


    /**
     * Render data with resource
     * @param $template
     * @param array $data
     * @param array $sections
     * @return string
     */
    public function render($template, $data = [], array $sections = [])
    {
        // fix array
        if(!is_array($data)) {
            $data = [];
        }

        // define data
        $template = $this->templates . $template . $this->ext;
        $data = array_merge((array)$this, $data);

        // create template & compile
        $template = new Template($template, $data, $sections, $this->helpers, $this);
        $content = $template->compile();

        // layout ?
        if($parent = $template->parent()) {

            // extract layout data
            list($layout, $data, $sections) = $parent;

            // define data & render layout
            $data = array_merge((array)$this, $data);
            $content = $this->render($layout, $data, $sections);

        }

        return $content;
    }


    /**
     * Static instance rendering
     * @param string $template
     * @param array $data
     * @return string
     */
    public static function make($template, $data = [])
    {
        // parse path
        $dir = dirname($template);
        $filename = basename($template);

        // running engine on this directory ?
        if(!isset(static::$instances[$dir])) {
            static::$instances[$dir] = new self($dir);
        }

        // render
        return static::$instances[$dir]->render($filename, $data);
    }

}