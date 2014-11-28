<?php

namespace Craft\Http\Request;

class Cli
{

    /** @var string */
    public $command;

    /** @var array */
    public $argv = [];


    /**
     * Init cli data
     * @param string $command
     * @param array $argv
     */
    public function __construct($command = null, array $argv = [])
    {
        $this->command = $command;
        $this->argv = $argv;
    }


    /**
     * Create from env
     * @return static
     */
    public static function create()
    {
        // create cli request
        $cli = new static;

        // get argv
        $argv = isset($_SERVER['argv']) ? $_SERVER['argv'] : [];

        // kick filename
        array_shift($argv);

        // resolve command name
        if($argv and substr(current($argv), 0, 1) === '-') {
            $cli->command = null;
        }
        elseif($argv) {
            $cli->command = trim(array_shift($argv));
            $cli->argv = $argv;
        }

        return $cli;
    }

} 