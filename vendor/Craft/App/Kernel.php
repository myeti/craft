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
use Psr\Log\LogLevel;

/**
 * Very core app class :
 * manages events, services, io request/response
 */
class Kernel extends Event\Channel
{

    /**
     * Init with built-in services
     * @param Plugin $plugins
     */
    public function __construct(Plugin ...$plugins)
    {
        foreach($plugins as $plugin) {
            $this->plug($plugin);
        }
    }


    /**
     * Add plugin
     * @param Plugin $plugin
     * @return $this
     */
    public function plug(Plugin $plugin)
    {
        $plugin->listen($this);
        return $this;
    }


    /**
     * Handle context request
     * @param RequestInterface $request
     * @throws Internal
     * @throws \Exception
     * @return bool
     */
    public function handle(RequestInterface $request)
    {
        // init data
        $response = $e = null;

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
            $response = $this->error($e, $request);
        }
        // output any response
        finally {
            $this->respond($request, $response, $e);
            return $this->end($request, $response);
        }
    }


    /**
     * Start process
     * @param RequestInterface $request
     * @return RequestInterface
     */
    protected function start(RequestInterface $request)
    {
        // filter event
        $this->fire('kernel.start', $request);

        return $request;
    }


    /**
     * Execute request
     * @param RequestInterface $request
     * @return Response
     */
    protected function execute(RequestInterface $request)
    {
        // event filter
        $this->fire('kernel.request', $request);

        // not a valid callable
        if(!is_callable($request->action()->callable)) {
            throw new \BadMethodCallException('Request::action must be a valid callable.');
        }

        // run
        $data = call_user_func_array($request->action()->callable, $request->action()->args);

        // action returned response object
        if($data instanceof Response) {
            $response = $data;
        }
        // action returned content
        else {
            $response = new Response($data);
        }

        return $response;
    }


    /**
     * Send response
     * @param RequestInterface $request
     * @param Response $response
     * @param \Exception $e
     * @return $this
     */
    protected function respond(RequestInterface $request, Response $response, \Exception $e = null)
    {
        // has response
        if($response) {

            // no error, apply filter
            if(!$e) {
                $this->fire('kernel.response', $request, $response);
            }

            // send response to client
            $response->send();
        }

        return $this;
    }


    /**
     * End process
     * @param RequestInterface $request
     * @param Response $response
     * @return string
     */
    protected function end(RequestInterface $request, Response $response)
    {
        // filter event
        $this->fire('kernel.end', $request, $response);

        // execution time
        $now = microtime(true);
        $elapsed = number_format($now - $request->time(), 4);

        return $elapsed;
    }


    /**
     * Catch internal error
     * @param Internal $e
     * @param RequestInterface $request
     * @throws Internal
     * @return string
     */
    protected function internal(Internal $e, RequestInterface $request)
    {
        // create response
        $response = new Response;

        // http code error
        $caught = $this->fire($e->getCode(), $request, $response, $e);

        // not caught, try as normal error
        if(!$caught) {
            return $this->error($e, $request, LogLevel::WARNING);
        }

        // update request
        $request->error($e->getCode() . ' ' . $e->getMessage());

        // log error
        Logger::notice('Internal : ' . $request->error());

        return $response;
    }


    /**
     * Catch other error
     * @param \Exception $e
     * @param RequestInterface $request
     * @param string $level
     * @throws \Exception
     * @return string
     */
    protected function error(\Exception $e, RequestInterface $request, $level = LogLevel::CRITICAL)
    {
        // create message
        $class = get_class($e);
        $message = $class;

        // update request
        if($error = $e->getMessage()) {
            $request->error($error);
            $message .= ' "' . $error . '"';
        }
        else {
            $request->error($message);
        }

        // add file:line
        $message .= ' in ' . $e->getFile() . ':' . $e->getLine();

        // create response
        $response = new Response;

        // log error
        Logger::log($level, $message);

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