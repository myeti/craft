<?php

namespace App\Cli;

use Craft\App\Cli;

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
        Cli::say('Not implement yet')->ln();
    }

}