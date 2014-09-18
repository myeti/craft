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

    const VERSION = 0.9;

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
     * @param Command $command
     * @return $this
     */
	public function add(Command $command)
	{
		$this->commands[$command->name] = $command;
        return $this;
	}


    /**
     * Register command
     * @param Command $command
     * @return $this
     */
	public function raw($name, $param = null, callable $command)
	{
		$this->commands[$command->name] = $command;
        return $this;
	}


    /**
     * Let's go !
     * @param string $args
     * @return bool
     */
	public function run($args = null)
	{
		// parse query
        if(!$args) {
            $args = $_SERVER['argv'];
            array_shift($args);
        }
        else {
            $args = explode(' ', $args);
        }

        // get command name
        $name = array_shift($args);

        // welcome
        if(!$name or $name[0] == '-') {

            if($name) {
                array_unshift($args, $name);
            }

            return $this->welcome->run($args);
        }

        // command not found
        if(!isset($this->commands[$name])) {
            return $this->out('Unknown command "' . $name . '".');
        }

        // execute command
        $error = $this->commands[$name]->run($args);

        // error
        if($error) {
            return $this->out($error);
        }

        return true;
	}


    /**
     * Output message
     * @param $message
     * @return bool
     */
    protected function out($message)
    {
        Dialog::say($message);
        return false;
    }

}