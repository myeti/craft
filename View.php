<?php

namespace craft;

class View
{

    /** @var array */
    protected $_config = [
        'dir' => null,
        'vars' => []
    ];


    /**
     * Setup with config
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->_config = $config + $this->_config;
    }

    /**
     * Add custom var
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->_config['vars'][$key] = $value;
    }

    /**
     * Render data
     * @param $vars
     * @param array $options
     * @throws \LogicException
     */
    public function render($vars, array $options = [])
    {
        // default options
        $options = [
            'render' => 'template',
            'view' => null
        ] + $options;

        // dispatch to specific render method
        $method = 'render' . ucfirst($options['render']);
        if(method_exists($this, $method)){
            $this->{$method}($vars, $options);
        }
        else {
            throw new \LogicException('Render engine does not know "' . $options['render'] . '"');
        }
    }

    /**
     * Raw render
     * @param $vars
     * @param $options
     */
    public function renderRaw($vars, $options)
    {
        echo is_array($vars)
            ? print_r($vars)
            : $vars;
    }

    /**
     * Json render
     * @param $vars
     * @param $options
     */
    public function renderJson($vars, $options)
    {
        echo json_encode($vars, JSON_PRETTY_PRINT);
    }

    /**
     * Template render
     * @param $vars
     * @param $options
     * @throws \LogicException
     */
    public function renderTemplate($vars, $options)
    {
        if(empty($options['view'])){
            throw new \LogicException('Render engine needs a valid template file');
        }

        $template = new Template($this->_config['dir'] . $options['view'], $vars);
        echo $template;
    }

}