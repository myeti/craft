<?php

namespace Craft\App\Cli;

use Craft\App\Cli;

class Landing extends  Cli\Command
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
        Cli::say('Welcome to the Craft cli interface')->ln();
    }

}