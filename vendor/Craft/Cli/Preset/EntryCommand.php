<?php

namespace Craft\Cli\Preset;

use Craft\Cli\Console;
use Craft\Cli\Command;

class EntryCommand extends Command
{

    /**
     * Execute command
     * @param object $args
     * @param object $options
     * @return mixed
     */
    public function run($args, $options)
    {
        Console::say('Welcome to the Craft Cli !')->ln();
    }

}