<?php

namespace Craft\Router;

class Route
{

    /** @var string */
    public $name;

    /** @var string */
    public $path;

    /** @var mixed */
    public $target;

    /** @var array */
    public $context = [];

    /** @var array */
    public $data = [];


    /**
     * Setup route
     * @param string $name
     * @param string $path
     * @param mixed $target
     * @param array $context
     */
    public function __construct($name, $path, $target, array $context = [])
    {
        $this->name = $name;
        $this->path = $path;
        $this->target = $target;
        $this->context = $context;
    }

} 