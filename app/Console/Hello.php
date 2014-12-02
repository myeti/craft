<?php

namespace App\Console;

use Craft\Cli;

class Hello extends  Cli\Command
{

    /** @var string */
    public $description = 'Sample command';


    /**
     * Run the command
     * @param $args
     * @param $options
     */
    public function run($args, $options)
    {
        Cli\Dialog::say('Hello you :D !');
    }

}