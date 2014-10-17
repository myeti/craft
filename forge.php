<?php

require 'vendor/autoload.php';

use Craft\Cli;

$cli = new Cli\Console;

// add user commands here
//$cli->add(new App\Command\YourCommand);

$cli->run();