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

use Craft\Event;
use Craft\Trace\Logger;

/**
 * Advanced Dispatcher :
 * manages inner events
 * and plug services
 */
class Kernel extends Dispatcher implements Event\Trigger
{

    use Event\Delegate;


    /**
     * Define inner event channel
     */
    public function __construct(Event\Trigger $channel = null)
    {
        $this->channel = $channel ?: new Event\Channel;
    }


    /**
     * Add service
     * @param Service $service
     * @return $this
     */
    public function plug(Service $service)
    {
        $service->listen($this);
        Logger::info('Add service ' . get_class($service));

        return $this;
    }


    /**
     * Handle context request
     * @param Request $request
     * @throws Internal
     * @throws \Exception
     * @return bool
     */
    public function handle(Request $request = null)
    {
        try {

            // start
            $request = $this->start($request);

            // execute request
            $response = $this->execute($request);

            // send response & finish
            return $this->respond($request, $response)->end($request, $response);
        }
        // internal error
        catch(Internal $e) {

            // error caught
            $response = $this->internal($e, $request);

            // send response & finish
            return $this->respond($request, $response, $e)->end($request, $response);
        }
        // normal error
        catch(\Exception $e) {

            // error caught
            $response = $this->error($e, $request);

            // send response & finish
            return $this->respond($request, $response, $e)->end($request, $response);
        }

    }


    /**
     * Start process
     * @param Request $request
     * @return Request
     */
    protected function start(Request $request = null)
    {
        // create default request
        if(!$request) {
            $request = new Request;
        }

        // filter event
        $this->fire('kernel.start');

        // log starting
        Logger::info('Kernel start');

        return $request;
    }


    /**
     * Execute request
     * @param Request $request
     * @return Response
     */
    protected function execute(Request $request)
    {
        // event filter
        $this->fire('kernel.request', $request);

        // dispatcher process
        $response = parent::handle($request);

        // log execution
        Logger::info('Request executed');

        return $response;
    }


    /**
     * Send response
     * @param Request $request
     * @param Response $response
     * @param \Exception $e
     * @return $this
     */
    protected function respond(Request $request, Response $response, \Exception $e = null)
    {

        // no error, apply filter
        if(!$e) {
            $this->fire('kernel.response', $request, $response);
            $log = 'Response ' . $response->code . ' sent';
        }
        else {
            $log = 'Response ' . $response->code . ' sent with error(s)';
        }

        // send response to client
        $response->send();

        // log sending
        Logger::info($log);

        return $this;
    }


    /**
     * End process
     * @param Request $request
     * @param Response $response
     * @return string
     */
    protected function end(Request $request, Response $response)
    {
        // filter event
        $this->fire('kernel.end', $request, $response);

        // execution time
        $now = microtime(true);
        $elapsed = number_format($now - $request->start, 4);

        // finish log
        Logger::info('Kernel end - execution time : ' . $elapsed . 's');

        return $elapsed;
    }


    /**
     * Catch internal error
     * @param Internal $e
     * @param Request $request
     * @throws Internal
     * @return string
     */
    protected function internal(Internal $e, Request $request)
    {
        // create response
        $response = new Response;

        // http code error
        $caught = $this->fire($e->getCode(), $request, $response, $e);

        // not caught, try as normal error
        if(!$caught) {
            return $this->error($e, $request);
        }

        // update request
        $request->error = $e->getCode() . ' ' . $e->getMessage();

        // log error
        Logger::notice('Internal : ' . $request->error);

        return $response;
    }


    /**
     * Catch other error
     * @param \Exception $e
     * @param Request $request
     * @throws \Exception
     * @return string
     */
    protected function error(\Exception $e, Request $request)
    {
        // create message
        $class = get_class($e);
        $message = $class;

        // update request
        if($e->getMessage()) {
            $request->error = $e->getMessage();
            $message .= ' "' . $request->error . '"';
        }
        else {
            $request->error = $message;
        }

        // add file:line
        $message .= ' in ' . $e->getFile() . ':' . $e->getLine();

        // create response
        $response = new Response;

        // log error
        Logger::critical($message);

        // specific & general error
        $caught = $this->fire('kernel.error.' . $class, $request, $response, $e);
        $caught += $this->fire('kernel.error', $request, $response, $e);

        // not caught
        if(!$caught) {
            throw $e;
        }

        return $response;
    }

}