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

use Craft\Trace\Logger;

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
     * @param Response $response
     * @throws \BadMethodCallException
     * @return Response
     */
    public function handle(Request $request, Response $response = null)
    {
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

        // user returned response object
        if($data instanceof Response) {
            $response = $data;
        }
        // user returned printable content
        elseif(is_string($data)) {
            $response = new Response($data);
        }
        // user returned mixed data
        else {
            $response = new Response;
            $response->data = $data;
        }

        Logger::info('App.Dispatcher : response generated');

        return $response;
    }

}


