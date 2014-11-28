<?php

namespace App\Console;

use Craft\App\Console;

class Hello extends  Console\Command
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
        Console\Dialog::say('Hello you :D !');
    }

}