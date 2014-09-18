<?php

namespace Craft\Cli\Command;

class Argument
{

    /** @var string */
    public $name;

    /** @var bool */
    public $required = true;

    /** @var mixed */
    public $default;


    /**
     * New argument
     * @param string $name
     * @param bool $required
     * @param mixed $default
     */
    public function __construct($name, $required = true, $default = null)
    {
        $this->name = $name;
        $this->required = $required;
        $this->default = $default;
    }

} 