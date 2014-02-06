<?php

// load craft package
require 'vendor/autoload.php';

use Craft\Cli\Console;
use Craft\Cli\Out;

// template files
$files = [
    'auth.logic'            => 'auth/logic',
    'auth.view.login'       => 'auth/view.login',
    'auth.view.register'    => 'auth/view.register',
    'crud.logic'            => 'crud/logic',
    'crud.view.all'         => 'crud/view.all',
    'crud.view.one'         => 'crud/view.one',
    'crud.view.field'        => 'crud/view.field'
];

$cli = new Console(function(){
    Out::say('Welcome to the Craft Wizard ~');
});

$cli->command(['auth :alias'], function($alias){
    Out::say('Create auth controller');
});

$cli->command('crud :alias', function($alias){
    Out::say('Create crud controller for "' . $alias . '" model.');
});

$cli->command('logic :name', function($name){
    Out::say('Create controller "' . $name . '"');
});

$cli->command('fuck off', function($name){
    Out::say('You too.');
});

$cli->plug();