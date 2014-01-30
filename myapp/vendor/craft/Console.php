<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft;

use Craft\App\Router;
use Craft\Box\Meta\Resolver;
use Craft\Console\Out;

class Console
{

	/** @var array */
	protected $commands = [];


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
		$this->commands[$command] = $callback;
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
        $router = new Router($this->commands);

        // look up command
        $route = $router->find($command);

        // error
        if(!$route) {
            Out::say('Unknown command "' . $command . '"');
            exit;
        }

        // execute
        return Resolver::call($route->target, $route->args);
	}

}