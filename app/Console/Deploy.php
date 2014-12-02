<?php

namespace App\Console;

use Craft\Cli;

class Deploy extends  Cli\Command
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
        Cli\Dialog::say('Not implement yet');
    }

}