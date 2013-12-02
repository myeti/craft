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
    protected $_file;

    /** @var array */
    protected $_vars = [];

    /** @var array */
    protected $_slots = [];

    /** @var View */
    public $layout;

    /** @var array */
    protected static $_globalVars = [];


    /**
     * @param $file
     * @param array $vars
     * @throws \InvalidArgumentException
     */
    public function __construct($file, array $vars = [])
    {
        // clean
        $file = strtolower($file);
        if(substr($file, -4) != '.php') {
            $file .= '.php';
        }

        // view exists ?
        if(!file_exists($file)) {
            throw new \RuntimeException('Template "' . $file . '" does not exist.');
        }

        $this->_file = $file;
        $this->_vars = $vars;
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
            foreach($name as $key => $value)
                $this->_vars[$key] = $value;
        }
        else
            $this->_vars[$name] = $value;

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
        $this->_slots[$name] = $content;
        return $this;
    }


    /**
     * Inner access : Display slot
     * @param $name
     * @return mixed
     */
    protected function hook($name)
    {
        return empty($this->_slots[$name])
            ? null
            : $this->_slots[$name];
    }


    /**
     * Inner access : Set layout
     * @param $file
     * @param array $vars
     */
    protected function layout($file, array $vars = [])
    {
        $this->layout = new self($file, $vars);
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
     * Inner access : Import raw partial
     * @param $file
     * @return string
     */
    protected function load($file)
    {
        $partial = new self($file);
        return $partial->compile();
    }


    /**
     * Copy data to another view
     */
    public function copyTo(View &$view)
    {
        // give slots
        foreach($this->_slots as $slot => $value)
            $view->slot($slot, $value);

        // give vars
        foreach($this->_vars as $name => $arg)
            $view->set($name, $arg);
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
        extract(array_merge($this->_vars, static::$_globalVars));

        // import views
        require $this->_file;

        // get content
        return ob_get_clean();
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
    public static function vars(array $vars)
    {
        static::$_globalVars = array_merge(static::$_globalVars, $vars);
    }

}