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

    /** @var Command */
    protected $entry;

	/** @var Command[] */
	protected $commands = [];


    /**
     * Define cli interface
     * @param array $commands
     */
    public function __construct(array $commands = [])
    {
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

            // entry command
            if(!$command->name) {
                $this->entry = $command;
            }
            // stack command
            else {
                $this->commands[$command->name] = $command;
            }

        }

        return $this;
	}


    /**
     * Create raw command
     * @param string $name
     * @param string $description
     * @return $this
     */
	public function &set($name, $description)
	{
        $command = new Command\Raw($name, $description);
		$this->commands[$command->name] = $command;
        return $command;
	}


    /**
     * Let's go !
     * @return bool
     */
	public function run()
	{
        // add preset commands
        $this->add(new Preset\EntryCommand);
        $this->add(new Preset\ListCommand($this->commands));

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

        // entry command
        if(!$name) {
            return $this->entry->valid($argv);
        }

        // unknown command
        if(!isset($this->commands[$name])) {
            Console::say('unknown command "', $name, '"');
        }

        // valid & run command
        return $this->commands[$name]->valid($argv);
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