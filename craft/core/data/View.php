<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\core\data;

class View
{

    /** @var string */
    public $template;

    /** @var array */
    public $vars = [];

    /** @var array */
    public $slots = [];

    /** @var View */
    public $layout;

    /** @var array */
    public static $globals = [];


    /**
     * @param string $template
     * @param array $vars
     */
    public function __construct($template, array $vars = [])
    {
        $this->template = $template;
        $this->vars = $vars;
    }


    /**
     * Assign var
     * @param $name
     * @param null $value
     * @return $this
     */
    public function set($name, $value)
    {
        if(is_array($name)) {
            foreach($name as $key => $value) {
                $this->vars[$key] = $value;
            }
        }
        else {
            $this->vars[$name] = $value;
        }

        return $this;
    }


    /**
     * Add content to slot
     * @param $name
     * @param $content
     * @return $this
     */
    public function slot($name, $content)
    {
        $this->slots[$name] = $content;
        return $this;
    }


    /**
     * Display slot content
     * @param $name
     * @return mixed
     */
    protected function hook($name)
    {
        return empty($this->slots[$name]) ? null : $this->slots[$name];
    }


    /**
     * Inner access : Shortcut content slot
     * @return string
     */
    protected function content()
    {
        return $this->hook('content');
    }


    /**
     * Set layout
     * @param string $template
     * @param array $vars
     */
    protected function layout($template, array $vars = [])
    {
        $this->layout = new self($template, $vars);
    }


    /**
     * Import raw partial
     * @param string $template
     * @return string
     */
    protected function load($template)
    {
        $partial = new self($template);
        return $partial->compile();
    }


    /**
     * Compile and return content
     * @return string
     */
    public function compile()
    {
        // start streaming
        ob_start();

        // extract vars
        extract(array_merge($this->_vars, static::$globals));

        // import views
        require $this->_file;

        // get content
        $content = ob_get_clean();

        // compile layout
        if($this->layout instanceof View) {

            // give params
            $this->layout->vars = array_merge($this->layout->vars, $this->vars);
            $this->layout->slots = array_merge($this->layout->slots, $this->slots);
            $this->layout->slot('content', $content);

            // compile
            $content = $this->layout->compile();
        }

        return $content;
    }


    /**
     * Meta markup
     * @return string
     */
    protected function meta()
    {
        $meta = "\n\t" . '<meta charset="UTF-8">';
        $meta .= "\n\t" . '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1" />';

        return $meta . "\n";
    }


    /**
     * CSS markup
     * @return string
     */
    protected function css()
    {
        $str = '';
        foreach(func_get_args() as $file) {

            if(substr($file, -4) == '.css') {
                $file = substr($file, 0, -4);
            }

            $str .= "\n\t" . '<link type="text/css" media="screen" href="' . static::asset($file . '.css') . '" rel="stylesheet" />';
        }

        return $str . "\n";
    }


    /**
     * JS markup
     * @return string
     */
    protected function js()
    {
        $str = '';
        foreach(func_get_args() as $file) {

            if(substr($file, -3) == '.js') {
                $file = substr($file, 0, -3);
            }

            $str .= "\n\t" . '<script type="text/javascript" src="' . static::asset($file . '.js') . '"></script>';
        }

        return $str . "\n";
    }


    /**
     * Asset public file
     * @param $filename
     * @return string
     */
    protected function asset($filename)
    {
        return url() . $filename;
    }


    /**
     * Add general vars
     * @param $vars array
     */
    public static function globals(array $vars)
    {
        static::$globals = array_merge(static::$globals, $vars);
    }

}