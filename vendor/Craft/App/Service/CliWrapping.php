<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Service;

use Craft\App;
use Craft\Router;

/**
 * Check if user is allowed to execute
 * the requested action when @auth is specified.
 *
 * Needs Service\RequestResolver
 */
class CliWrapping extends App\Service
{

    /**
     * Init basic routes
     * @param Router\Seeker $router
     */
    public function __construct(Router\Seeker &$router)
    {
        // get all commands
        $commands = [];
        foreach($router->routes() as $route) {
            $commands[$route->query] = $route->action;
        }

        // set landing & listing command
        $router->add(new Router\Route(null, new App\Cli\Landing));
        $router->add(new Router\Route('list', new App\Cli\Listing($commands)));
    }


    /**
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return [
            'kernel.start' => 'onKernelStart',
            'kernel.request' => 'onKernelRequest',
            404 => 'onNotFound',
            400 => 'onBadRequest'
        ];
    }


    /**
     * Handle kernel request
     * @param App\Request $request
     */
    public function onKernelStart(App\Request $request)
    {
        // get argv
        $argv = $request->context->args;

        // resolve command name
        if(substr(current($argv), 0, 1) === '-') {
            $request->query = null;
        }
        else {
            $request->query = trim(array_shift($argv));
            $request->args = $argv;
        }
    }


    /**
     * Handle kernel request
     * @param App\Request $request
     * @throws App\Internal\BadRequest
     */
    public function onKernelRequest(App\Request $request)
    {
        // init
        $args = $options = [];
        $argv = $request->args;

        /** @var App\Cli\Command $command */
        $command = $request->action;
        if(!is_object($command)) {
            $command = new $command;
        }

        // parse args
        foreach($command->args() as &$arg) {

            // init
            $args[$arg->name] = false;

            // is valid argument ?
            $valid = (substr(current($argv), 0, 1) !== '-');

            // is required & not valid argument
            if($arg->isRequired() and (!$argv or !$valid)) {
                throw new App\Internal\BadRequest('missing argument "', $arg->name, '"');
            }
            // valid argument
            elseif($valid) {
                $args[$arg->name] = array_shift($argv);
            }
            // not valid argument
            else {
                break;
            }

        }

        // prepare argv for options parsing
        $query = implode('&', $argv);
        foreach($command->options() as $opt) {
            if($opt->isMultiple()) {
                $query = str_replace('-' . $opt->name, '-' . $opt->name . '[]', $query);
            }
        }
        parse_str($query, $argv);

        // parse options
        foreach($command->options() as $opt) {

            // init
            $options[$opt->name] = $opt->isMultiple() ? array() : false;
            $key = (strlen($opt->name) === 1 ? '-' : '--') . $opt->name;

            // option exists
            if(isset($argv[$key])) {

                // clean
                if($argv[$key] == '') {
                    $argv[$key] = null;
                }

                // error : must not have a value
                if($opt->isEmpty() and $argv[$key] != null) {
                    throw new App\Internal\BadRequest('option "', $opt->name, '" accepts no value');
                }
                // error : must have a value
                elseif($opt->isRequired() and $argv[$key] == null) {
                    throw new App\Internal\BadRequest('option "', $opt->name, '" must have a value');
                }
                // error : must have one or many value
                elseif($opt->isMultiple() and empty($argv[$key])) {
                    throw new App\Internal\BadRequest('option "', $opt->name, '" must have at least one value');
                }

                // valid value, set option
                $options[$opt->name] = ($argv[$key] == null) ? true : $argv[$key];

                unset($argv[$key]);
            }

        }

        // unknown params
        if($argv) {
            $name = is_int(key($argv)) ? current($argv) : key($argv);
            throw new App\Internal\BadRequest('unknown parameter "', $name, '"');
        }

        // prepare action
        $request->action = [$command, 'run'];
        $request->params = [$args, $options];
    }


    /**
     * Handle command not found
     * @param App\Request $request
     */
    public function onNotFound(App\Request $request)
    {
        App\Cli::say('unknown command "', $request->query, '"')->ln();
    }


    /**
     * Handle command not found
     * @param App\Request $request
     */
    public function onBadRequest(App\Request $request, App\Response $response, App\Internal $e)
    {
        App\Cli::say($e->getMessage())->ln();
    }

}