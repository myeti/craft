<?php

namespace Craft\Cli\Preset;

use Craft\Cli\Console;
use Craft\Cli\Command;

class ListCommand extends Command
{

    /** @var string */
    public $name = 'list';

    /** @var Command[] */
    protected $list;


    /**
     * Init list
     * @param array $list
     */
    public function __construct(array $list)
    {
        $this->list = $list;
        unset($this->list[$this->name]);
    }


    /**
     * Execute command
     * @param object $args
     * @param object $options
     * @return mixed
     */
    public function run($args, $options)
    {
        // no commands
        if(!$this->list) {
            Console::say('no registered commands yet')->ln();
        }
        else {
            foreach($this->list as $command){
                Console::say($command->name, "\t", $command->description)->ln();
            }
        }
    }

}