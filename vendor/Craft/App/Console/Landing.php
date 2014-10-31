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
     * @return mixed|void
     */
    public function run($args, $options)
    {
        Console::say('Welcome to the Craft cli interface')->ln();
    }

}