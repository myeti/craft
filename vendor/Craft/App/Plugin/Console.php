<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Plugin;

use Craft\Cli;
use Craft\App;
use Craft\Kit\Action;
use Craft\Router;

/**
 * Handle cli routing and command running
 */
class Console extends App\Plugin
{

    /** @var Router\Seeker */
    protected $router;


    /**
     * Init basic routes
     * @param Router\Seeker $router
     */
    public function __construct(Router\Seeker &$router)
    {
        $this->router = $router;
    }


    /**
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return [
            'kernel.start'   => 'onKernelStart',
            'kernel.request' => 'onKernelRequest',
            'kernel.error'   => 'onKernelError',
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
        // get all commands
        $commands = [];
        foreach($this->router->routes() as $route) {
            $commands[$route->query] = $route->action;
        }

        // set landing & listing command
        $this->router->add(new Router\Route(null, new Cli\Preset\Landing));
        $this->router->add(new Router\Route('list', new Cli\Preset\Listing($commands)));
    }


    /**
     * Handle kernel request
     * @param App\Request $request
     * @throws App\Internal\BadRequest
     * @throws App\Internal\NotFound
     */
    public function onKernelRequest(App\Request $request)
    {
        // find route from cli command
        $route = $this->router->find($request->cli()->command);

        // 404
        if(!$route) {
            throw new App\Internal\NotFound('Unknown command ' . $request->cli()->command);
        }

        // init
        $args = $options = [];
        $argv = $request->cli()->argv;

        /** @var Cli\Command $command */
        $command = $route->action;
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
        $action = new Action([$command, 'run'], [$args, $options]);

        // update request
        $request->action($action);
        $request->route($route);
    }


    /**
     * Handle command not found
     * @param App\Request $request
     */
    public function onNotFound(App\Request $request)
    {
        Cli\Dialog::say('unknown command ', $request->cli()->command);
    }


    /**
     * Handle bad command line
     * @param $req
     * @param $res
     * @param \Exception $e
     * @internal param App\Request $request
     */
    public function onBadRequest($req, $res, \Exception $e)
    {
        Cli\Dialog::say($e->getMessage());
    }


    /**
     * Handle error
     * @param App\Request $request
     * @param App\Response $response
     * @param \Exception $e
     */
    public function onKernelError(App\Request $request, App\Response $response, \Exception $e)
    {
        Cli\Dialog::say('Error : ' . $e->getMessage());
    }

}