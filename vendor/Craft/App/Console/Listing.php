<?php

namespace Craft\App\Console;

use Craft\App\Console;

class Listing extends  Console\Command
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
            Console::say('no command registered...')->ln();
        }
        else {
            Console::say('registered commands :')->ln();
            foreach($this->commands as $name => $command) {

                // get description
                $desc = is_object($command)
                    ? $command->description
                    : get_class_vars($command)['description'];

                // display line
                Console::say('- ' . $name . "\t\t" . $desc)->ln();
            }
        }
    }

}