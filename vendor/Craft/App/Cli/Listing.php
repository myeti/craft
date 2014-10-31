<?php

namespace Craft\App\Cli;

use Craft\App\Cli;

class Listing extends  Cli\Command
{

    /** @var string */
    public $description = 'List all registered commands';

    /** @var array */
    protected $commands = [];


    /**
     * register commands
     * @param array $commands
     */
    public function __construct(array $commands)
    {
        $this->commands = $commands;
    }


    /**
     * Run the command
     * @param $args
     * @param $options
     * @return mixed|void
     */
    public function run($args, $options)
    {
        if(!$this->commands) {
            Cli::say('no command registered...')->ln();
        }
        else {
            Cli::say('registered commands :')->ln();
            foreach($this->commands as $name => $command) {

                // get description
                $desc = is_object($command)
                    ? $command->description
                    : get_class_vars($command)['description'];

                // display line
                $offset = strlen($name);
                $offset = 15 - $offset;
                if($offset <= 4) {
                    $offset = 4;
                }

                Cli::say('- ' . $name . str_repeat(' ', $offset) . $desc)->ln();
            }
        }
    }

}