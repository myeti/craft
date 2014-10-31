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
use Craft\Debug\Logger;

/**
 * Very core app class :
 * manages events, services, io request/response
 */
class Kernel extends Event\Channel
{


    /**
     * Add service
     * @param Service $service
     * @return $this
     */
    public function plug(Service $service)
    {
        $service->listen($this);
        return $this;
    }


    /**
     * Auto-resolve request and run
     * @param mixed $query
     * @return bool
     */
    public function run($query = null)
    {
        $request = new Request($query);
        return $this->handle($request);
    }


    /**
     * Handle context request
     * @param Request $request
     * @throws Internal
     * @throws \Exception
     * @return bool
     */
    public function handle(Request $request)
    {
        // init data
        $response = $error = null;

        // resolve & execute request
        try {
            $request = $this->start($request);
            $response = $this->execute($request);
        }
        // catch internal event
        catch(Internal $e) {
            $response = $this->internal($e, $request);
        }
        // catch normal error
        catch(\Exception $e) {
            $error = $e;
            $response = $this->error($e, $request);
        }
        // output any response
        finally {
            $this->respond($request, $response, $error);
            return $this->end($request, $response);
        }
    }


    /**
     * Start process
     * @param Request $request
     * @return Request
     */
    protected function start(Request $request)
    {
        // filter event
        $this->fire('kernel.start', $request);

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

        // not a valid callable
        if(!is_callable($request->action)) {
            throw new \BadMethodCallException('Request::action must be a valid callable.');
        }

        // run
        $data = call_user_func_array($request->action, $request->params);

        // action returned response object
        if($data instanceof Response) {
            $response = $data;
        }
        // action returned printable content
        elseif(is_string($data)) {
            $response = new Response($data);
        }
        // action returned mixed data
        else {
            $response = new Response;
            $response->data = $data;
        }

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
        // no response
        if(!$response) {
            Logger::info('No response to send');
            return $this;
        }

        // no error, apply filter
        if(!$e) {
            $this->fire('kernel.response', $request, $response);
            $log = 'Response ' . $response->code . ' sent';
        }
        // had error, skip filter
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
        $elapsed = number_format($now - $request->time, 4);

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