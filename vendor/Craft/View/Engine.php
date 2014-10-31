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
    public $assets;

    /** @var string */
    protected $ext = '.php';

    /** @var callable[] */
    protected $helpers = [];

    /** @var Engine[] */
    protected static $instances = [];


    /**
     * Setup engine
     * @param string $templates path
     * @param string $assets url
     */
    public function __construct($templates = null, $assets = null)
    {
        // set directories
        $this->templates = rtrim($templates, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->assets = $assets ? rtrim($assets, '/') . '/' : url('/');

        // text helper
        $this->set('e', '\Craft\Data\Text\String::escape');
        $this->set('t', '\Craft\Data\Text\Lang::translate');

        // box helper
        $this->set('session', '\Craft\Box\Session::get');
        $this->set('flash', '\Craft\Box\Flash::get');
        $this->set('user', '\Craft\Box\Auth::user');
        $this->set('rank', '\Craft\Box\Auth::rank');

        // assets helper
        $this->set('asset', [$this, 'asset']);
        $this->set('css', [$this, 'css']);
        $this->set('js', [$this, 'js']);

        // engine helper
        $this->set('partial', [$this, 'render']);
    }


    /**
     * Set helper function
     * @param string $fn
     * @param callable $callback
     * @return $this
     */
    public function set($fn, callable $callback)
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
     * Get asset path
     * @param string $filename
     * @param string $ext
     * @return string
     */
    public function asset($filename, $ext = null)
    {
        if($ext) {
            $ext = '.' . ltrim($ext, '.');
        }
        return $this->assets . ltrim($filename, '/') . $ext;
    }


    /**
     * Css tag
     * @param $files
     * @return string
     */
    public function css(...$files)
    {
        $css = [];
        foreach($files as $file) {
            $css[] = '<link type="text/css" href="' . $this->asset($file, '.css') . '" rel="stylesheet" />';
        }
        return implode("\n    ", $css) . "\n";
    }


    /**
     * Js tag
     * @param $files
     * @return string
     */
    public function js(...$files)
    {
        $js = [];
        foreach($files as $file) {
            $js[] = '<script type="text/javascript" src="' . $this->asset($file, '.js')  . '"></script>';
        }
        return implode("\n    ", $js) . "\n";
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