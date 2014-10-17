<?php

namespace Craft\Cli\Command;

use Craft\Cli\Command;

class Raw extends Command
{

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
     * Attach action
     * @param callable $closure
     * @return $this
     */
    public function execute(\Closure $closure)
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
    public function run($args, $options)
    {
        call_user_func($this->closure);
    }

}