<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\cli;

use craft\box\meta\Action;
use craft\kit\router\Router;

class Console
{

	/** @var array */
	protected $_commands = [];


    /**
     * Register landing command
     * @param  \Closure $callback
     */
    public function __construct(\Closure $callback)
    {
        static::command(null, $callback);
    }


    /**
     * Register command
     * @param $command
     * @param \Closure $callback
     * @internal param string $name
     */
	public function command($command, \Closure $callback)
	{
		$this->_commands[$command] = $callback;
	}


	/**
	 * Let's go !
	 */
	public function plug()
	{
		// make command
		$argv = $_SERVER['argv'];
		array_shift($args);
        $command = implode(' ', $argv);

        // setup router
        $router = new Router($this->_commands);

        // look up command
        $route = $router->find($command);

        // error
        if(!$route) {
            Out::say('Unknown command "' . $command . '"');
            exit;
        }

        // execute
        return Action::call($route->target, $route->args);
	}

}