<?php

namespace craft\kit\cli;

class Command
{

    /** @var string */
    public $command;

    /** @var array */
    public $params = [];

    /** @var \Closure */
    public $callback;


    /**
     * Create command
     * @param $command
     * @param array $params
     * @param null $callback
     */
    public function __construct($command, array $params = [], $callback = null)
    {
        $this->command = $command;
        $this->params = $params;
        $this->callback = $callback;
    }

} 