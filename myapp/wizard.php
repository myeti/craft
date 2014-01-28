<?php

// load craft package
require 'vendor/craft/package.php';

use craft\kit\cli\Console;
use craft\kit\cli\Out;
use craft\kit\cli\In;

// template files
$files = [
    'auth.logic'            => 'auth/logic',
    'auth.view.login'       => 'auth/view.login',
    'auth.view.register'    => 'auth/view.register',
    'crud.logic'            => 'crud/logic',
    'crud.view.all'         => 'crud/view.all',
    'crud.view.one'         => 'crud/view.one',
    'crud.view.form'        => 'crud/view.form'
];

$cli = new Console(function(){
    Out::say('Welcome to the Craft Wizard :)');
});

$cli->command(['auth', 'auth :alias'], function($alias = null){
    Out::say('Create auth controller');
});

$cli->command('crud :alias', function($alias){
    Out::say('Create crud controller for "' . $alias . '" model.');
});

$cli->command('logic :name', function($name){
    Out::say('Create controller "' . $name . '"');
});

$cli->plug();