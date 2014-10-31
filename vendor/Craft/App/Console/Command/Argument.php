<?php

namespace Craft\App\Console\Command;

class Argument
{

    const OPTIONAL = 1;
    const REQUIRED = 2;

    /** @var string */
    public $name;

    /** @var int */
    public $type = self::REQUIRED;

    /** @var mixed */
    public $value;


    /**
     * New argument
     * @param string $name
     * @param int $type
     */
    public function __construct($name, $type = self::REQUIRED)
    {
        $this->name = $name;
        $this->type = $type;
    }


    /**
     * Value is optional
     * @return bool
     */
    public function isOptional()
    {
        return ($this->type === self::OPTIONAL);
    }


    /**
     * Value is required
     * @return bool
     */
    public function isRequired()
    {
        return ($this->type === self::REQUIRED);
    }

} 