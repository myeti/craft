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
     */
    public function run($args, $options)
    {
        if(!$this->commands) {
            Dialog::say('no command registered...');
        }
        else {
            Dialog::say('registered commands :');
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

                Dialog::say('- ' . $name . str_repeat(' ', $offset) . $desc);
            }
        }
    }

}