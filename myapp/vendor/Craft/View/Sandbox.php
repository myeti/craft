<?php

namespace Craft\View;

abstract class Sandbox implements Viewable
{

    /** @var Engine */
    private $engine;

    /** @var string */
    private $template;

    /** @var string */
    private $layout;

    /** @var array */
    private $data = [];

    /** @var String[] */
    private $sections = [];

    /** @var string */
    private $section;

    /** @var bool */
    private $rendering = false;


    /**
     * Setup template with engine
     * @param Engine $engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }


    /**
     * @param $template
     * @param array $data
     */
    protected function layout($template, array $data = [])
    {
        $this->layout = [$template, $data];
    }


    /**
     * Start recording section
     * @param $name
     */
    protected function section($name)
    {
        $this->section = $name;
        ob_start();
    }


    /**
     * Stop recording section
     */
    protected function end()
    {
        $this->sections[$this->section] = ob_get_clean();
        $this->section = null;
    }


    /**
     * Insert section
     * @param $section
     * @return string
     */
    protected function block($section)
    {
        return isset($this->sections[$section]) ? $this->sections[$section] : null;
    }


    /**
     * Insert child content
     * @return string
     */
    protected function content()
    {
        return $this->block('__content__');
    }


    /**
     * Render partial template
     * @param $template
     * @param array $data
     * @param array $sections
     * @return string
     */
    protected function partial($template, array $data = [], array $sections = [])
    {
        return Template::forge($this->engine, $template, $data, $sections);
    }


    /**
     * Call helper
     * @param $helper
     * @param array $args
     * @return mixed
     */
    public function __call($helper, array $args = [])
    {
        return $this->engine->helper($helper, $args);
    }


    /**
     * Render template
     * @param $template
     * @param array $data
     * @param array $sections
     * @throws \LogicException
     * @return string
     */
    public function render($template, array $data = [], array $sections = [])
    {
        // init
        $this->template = $template;
        $this->data = $data + $this->data;
        $this->sections = $sections + $this->sections;

        // start rendering
        if($this->rendering) {
            throw new \LogicException('Template is already rendering.');
        }
        $this->rendering = true;

        // compile
        extract($this->engine->data($this->data));
        ob_start();
        require $this->engine->path($this->template);
        $content = ob_get_clean();

        // layout
        if($this->layout) {
            list($layout, $data) = $this->layout;
            $sections = array_merge($this->sections, ['__content__' => $content]);
            $content = Template::forge($this->engine, $layout, $data, $sections);
        }

        // clear
        $this->template = null;
        $this->data = null;
        $this->sections = null;
        $this->section = null;
        $this->layout = null;
        $this->rendering = false;

        return $content;
    }

} 