<?php

namespace Craft\View;

class Engine
{

    /** @var string */
    protected $root;

    /** @var string */
    protected $ext;

    /** @var Helper[] */
    protected $helpers = [];

    /** @var array */
    protected $data = [];


    /**
     * Setup root path
     * @param string $root
     * @param string $ext
     */
    public function __construct($root = null, $ext = null)
    {
        $this->root = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->ext = ltrim($ext, '.');
    }


    /**
     * Add global data
     * @param $key
     * @param $value
     * @return $this
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }


    /**
     * Mount helper
     * @param Helper helper
     * @return $this
     */
    public function mount(Helper $helper)
    {
        $this->helpers[] = $helper;
        return $this;
    }


    /**
     * Execute helper
     * @param $fn
     * @param array $args
     * @return mixed
     * @throws \LogicException
     */
    public function helper($fn, array $args = [])
    {
        foreach($this->helpers as $helper) {
            $fns = $helper->register();
            if(isset($fns[$fn])) {

                // valid helper ?
                if(is_callable($fns[$fn])) {
                    return call_user_func_array($fns[$fn], $args);
                }
                else {
                    throw new \LogicException('Helper "' . $fn . '" is not a valid callable.');
                }

            }
        }

        throw new \LogicException('Unknown helper "' . $fn . '".');
    }


    /**
     * Get root path
     * @param string $template
     * @return string
     */
    public function path($template)
    {
        return $this->root . $template . '.' . $this->ext;
    }


    /**
     * Merge with global data
     * @param array $data
     * @return array
     */
    public function data(array $data = [])
    {
        return array_merge($this->data, $data);
    }

} 