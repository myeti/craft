<?php

namespace App\Cli;

use Craft\App\Cli;

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
        Cli::say('Hello you :D !')->ln();
    }

}