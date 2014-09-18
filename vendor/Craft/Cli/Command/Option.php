<?php

namespace Craft\Cli\Command;

class Option extends Argument
{

    /** @var string */
    public $name;

    /** @var bool */
    public $value = true;

    /** @var mixed */
    public $default;


    /**
     * New argument
     * @param string $name
     * @param bool $value
     * @param mixed $default
     */
    public function __construct($name, $value = true, $default = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->default = $default;
    }

} 