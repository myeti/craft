<?php

namespace App\Console;

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
        Console\Dialog::say('Not implement yet');
    }

}