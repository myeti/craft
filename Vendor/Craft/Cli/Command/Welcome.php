<?php

namespace Craft\Cli\Command;

use Craft\Cli\Command;
use Craft\Cli\Console;
use Craft\Cli\Dialog;

class Welcome extends Command
{

    /**
     * Register arguments
     * @return mixed
     */
    protected function register()
    {
        $this->flag('version', 'Get the current cli version');
    }

    /**
     * Execute command
     * @param array $arguments
     * @param array $parameters
     * @param array $flags
     * @return mixed
     */
    public function execute(array $arguments, array $parameters, array $flags)
    {
        if($flags['version']) {
            Dialog::say('version', Console::VERSION, "\n");
        }
        else {
            Dialog::say('Welcome :)', "\n");
        }
    }
}