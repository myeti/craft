<?php

namespace Craft\Cli\Command;

namespace Craft\Cli\Preset;

use Craft\Cli;

class Raw extends Cli\Command
{

    /** @var string */
    protected $name;

    /** @var string */
    protected $description;

    /** @var \Closure */
    protected $closure;


    /**
     * Setup raw command
     * @param string $name
     * @param string $description
     */
    public function __construct($name, $description)
    {
        $this->name = $name;
        $this->description = $description;
    }


    /**
     * Get command name
     * @return string
     */
    public function name()
    {
        return $this->name;
    }


    /**
     * Get command name
     * @return string
     */
    public function description()
    {
        return $this->description;
    }


    /**
     * Attach action
     * @param callable $closure
     * @return $this
     */
    public function execute(callable $closure)
    {
        $this->closure = $closure;
        return $this;
    }


    /**
     * Execute command
     * @param object $args
     * @param object $options
     * @return mixed
     */
    protected function run($args, $options)
    {
        return call_user_func($this->closure);
    }

}