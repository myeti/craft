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

use Craft\Router\Matcher\UrlMatcher;
use Craft\Router\RouteProvider;

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
		$query = $_SERVER['argv'];
		array_shift($query);
        $query = implode(' ', $query);

        // setup router
        $router = new RouteProvider($this->commands);
        $matcher = new UrlMatcher($router);

        // look up command
        $route = $matcher->find($query);

        // error
        if(!$route) {
            Out::say('Unknown command "' . $query . '"');
            exit;
        }

        // execute
        return call_user_func_array($route->target, $route->data);
	}

}