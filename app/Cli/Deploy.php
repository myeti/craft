<?php

namespace App\Cli;

use Craft\App\Console;

class Deploy extends  Console\Command
{

    /** @var string */
    public $description = 'Deploy projet when pushing in production';


    /**
     * Run the command
     * @param $args
     * @param $options
     */
    public function run($args, $options)
    {
        Console::say('Not implement yet')->ln();
    }

}