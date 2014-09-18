<?php

namespace Craft\Cli\Command;

class MultipleOption extends Option
{

    /** @var array */
    public $default = [];


    /**
     * New argument
     * @param string $name
     * @param array $default
     */
    public function __construct($name, array $default = [])
    {
        parent::__construct($name);
        $this->default = $default;
    }

} 