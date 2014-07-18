<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App;

use Forge\Logger;

/**
 * The most basic class :
 * execute the action stored in
 * the Request object.
 */
class Dispatcher implements Handler
{

    /**
     * Run on request
     * @param Request $request
     * @throws \BadMethodCallException
     * @return Response
     */
    public function handle(Request $request)
    {
        Logger::info('App.Dispatcher : dispatcher start');

        // not a valid callable
        if(!is_callable($request->action)) {
            throw new \BadMethodCallException('Request::action must be a valid callable.');
        }

        // add request as last (and optional) method arg
        $args = $request->args;
        $args[] = &$request;

        // run
        $data = call_user_func_array($request->action, $args);
        Logger::info('App.Dispatcher : request executed');

        // create response
        $response = new Response();
        $response->data = $data;
        Logger::info('App.Dispatcher : response created, dispatcher end');

        return $response;
    }

}


