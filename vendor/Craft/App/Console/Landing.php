<?php

namespace Craft\App\Console;

use Craft\App\Console;

class Landing extends  Console\Command
{

    /** @var string */
    public $description = 'Welcome message';


    /**
     * Run the command
     * @param $args
     * @param $options
     */
    public function run($args, $options)
    {
        Dialog::say('Welcome to the Craft cli interface');
    }

}