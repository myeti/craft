<?php

namespace Craft\Cli\Preset;

use Craft\Cli;

class Landing extends Cli\Command
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
        Cli\Dialog::say('Welcome to the Craft cli interface');
    }

}