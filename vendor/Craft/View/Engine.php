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

class Engine extends \ArrayObject implements EngineInterface
{

    /** @var string */
    protected $root;

    /** @var string */
    protected $ext = 'php';

    /** @var Helper[] */
    protected $helpers = [];


    /**
     * Setup root path
     * @param string $root
     * @param string $ext
     */
    public function __construct($root = null, $ext = 'php')
    {
        // set bases
        $this->dir($root, $ext);

        // default helpers
        $this->mount(new Helper\Markup);
        $this->mount(new Helper\Box);

        $this->helper('partial', [$this, 'render']);

        parent::__construct();
    }


    /**
     * Set views dir
     * @param string $dir
     * @param string $ext
     */
    public function dir($dir, $ext = 'php')
    {
        $this->root = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->ext = '.' . ltrim($ext, '.');
    }


    /**
     * Add helper function
     * @param string $name
     * @param callable $helper
     * @return $this
     */
    public function helper($name, callable $helper)
    {
        $this->helpers[$name] = $helper;
        return $this;
    }


    /**
     * Mount helper object
     * @param Helper $helper
     * @return $this
     */
    public function mount(Helper $helper)
    {
        foreach($helper->register() as $name => $helper) {
            $this->helper($name, $helper);
        }
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
        $template = $this->root . $template . $this->ext;
        $data = array_merge((array)$this, $data);

        // create template
        $template = new Engine\Template($this, $template, $data, $sections, $this->helpers);

        // compile
        $content = $template->compile();

        // layout ?
        if($parent = $template->parent()) {

            // extract layout data
            list($layout, $data, $sections) = $parent;

            // define data
            $data = array_merge((array)$this, $data);

            // render layout
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
        static $instance;
        if(!$instance) {
            $instance = new self;
        }

        return $instance->render($template, $data);
    }

}