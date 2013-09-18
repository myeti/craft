<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 *
 * @author Aymeric Assier <aymeric.assier@gmail.com>
 * @date 2013-09-12
 * @version 0.1
 */
namespace craft;

class View
{

    /** @var string */
    protected $_file;

    /** @var View */
    protected $_layout;

    /** @var array */
    protected $_vars = [];

    /** @var array */
    protected $_slots = [];

    /** @var array */
    protected static $_globalVars = [];


    /**
     * @param $file
     * @param array $vars
     * @throws \InvalidArgumentException
     */
    public function __construct($file, array $vars = [])
    {
        $file = strtolower($file);

        if(!file_exists($file))
            throw new \InvalidArgumentException('Template [' . $file . '] does not exist', 404);

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
        $this->_layout = new self($file, $vars);
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
     * @throws \InvalidArgumentException
     */
    protected function load($file)
    {
        $partial = new self($file);
        $partial->display();
    }


    /**
     * Generate view
     * @return string
     */
    public function display()
    {
        // compile
        $content = $this->compile();

        // has layout ?
        if($this->_layout)
        {
            // give slots
            foreach($this->_slots as $slot => $value)
                $this->_layout->slot($slot, $value);

            // give vars
            foreach($this->_vars as $name => $arg)
                $this->_layout->set($name, $arg);

            // push content
            $this->_layout->slot('content', $content);
            return $this->_layout->display();
        }
        else
            return $content;
    }


    /**
     * Compile and return content
     * @return string
     */
    protected function compile()
    {
        // start streaming
        ob_start();

        // extract vars
        extract($this->_vars + static::$_globalVars);

        // import view
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
        foreach(func_get_args() as $file)
            $str .= "\n\t" . '<link type="text/css" media="screen" href="' . static::asset($file . '.css') . '" rel="stylesheet" />';

        return $str . "\n";
    }


    /**
     * JS markup
     * @return string
     */
    protected function js()
    {
        $str = '';
        foreach(func_get_args() as $file)
            $str .= "\n\t" . '<script type="text/javascript" src="' . static::asset($file . '.js') . '"></script>';

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
        static::$_globalVars = $vars + static::$_globalVars;
    }

}