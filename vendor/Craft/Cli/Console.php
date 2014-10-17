<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Cli;

class Console
{

    /** @var Command[] */
    protected $commands = [];


    /**
     * Define cli interface
     * @param array $commands
     */
    public function __construct(array $commands = [])
    {
        // add listing command
        $this->set('list', null)->execute([$this, 'listing']);

        // add user commands
        foreach($commands as $command) {
            $this->add($command);
        }
    }


    /**
     * Register command
     * @param Command $commands
     * @return $this
     */
    public function add(Command ...$commands)
    {
        foreach($commands as $command) {
            $this->commands[$command->name()] = $command;
        }

        return $this;
    }


    /**
     * Create raw command
     * @param string $name
     * @param string $description
     * @return Command\Raw
     */
    public function &set($name, $description)
    {
        $command = new Command\Raw($name, $description);
        $this->commands[$name] = $command;
        return $command;
    }


    /**
     * Let's go !
     * @return bool
     */
    public function run()
    {
        // parse argv
        $argv = $_SERVER['argv'];
        array_shift($argv);

        // resolve name
        if(substr(current($argv), 0, 1) === '-') {
            $name = null;
        }
        else {
            $name = trim(array_shift($argv));
        }

        // entry point
        if(!$name) {
            Console::say('Welcome to the Craft Cli !')->ln();
            return true;
        }

        // unknown command
        if(!isset($this->commands[$name])) {
            Console::say('unknown command "', $name, '"');
        }

        // valid & run command
        return $this->commands[$name]->valid($argv);
    }


    /**
     * List all registered commands
     * @return bool
     */
    public function listing()
    {
        // get commands list
        $commands = $this->commands;
        unset($commands['list']);

        // no commands
        if(!$commands) {
            Console::say('no registered commands yet')->ln();
            return false;
        }

        // display commands
        foreach($commands as $command){
            Console::say($command->name(), "\t", $command->description())->ln();
        }

        return true;
    }


    /**
     * Write message
     * @param string $message
     * @return Dialog
     */
    public static function say(...$message)
    {
        return static::dialog()->say(...$message);
    }


    /**
     * Write new line
     * @return Dialog
     */
    public static function ln()
    {
        return static::dialog()->ln();
    }


    /**
     * Get user input
     * @return string
     */
    public static function read()
    {
        return static::dialog()->read();
    }


    /**
     * Ask question and get user answer
     * @param $message
     * @return string
     */
    public static function ask(...$message)
    {
        return static::dialog()->ask(...$message);
    }


    /**
     * Dialog instance
     * @return Dialog
     */
    protected static function dialog()
    {
        static $dialog;
        if(!$dialog) {
            $dialog = new Dialog;
        }

        return $dialog;
    }

}