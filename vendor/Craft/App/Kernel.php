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

use Craft\App;
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
     * @throws Error\Internal
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
        catch(App\Internal $e) {

            // error caught
            $response = $this->internal($e, $request);

            // send response & finish
            return $this->respond($request, $response)->end($request, $response);
        }
        // normal error
        catch(\Exception $e) {

            // error caught
            $response = $this->error($e, $request);

            // send response & finish
            return $this->respond($request, $response)->end($request, $response);
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
     * @param Response $response
     * @param Request $request
     * @return $this
     */
    protected function respond(Request $request, Response $response)
    {
        // event filter
        $this->fire('kernel.response', $request, $response);

        // send response to client
        $response->send();

        // log sending
        Logger::info('Response sent with code ' . $response->code);

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
    protected function internal(App\Internal $e, Request $request)
    {
        // create response
        $response = new Response;

        // http code error
        Logger::error('Internal : ' . $e->getCode() . ' ' . $e->getMessage());
        $caught = $this->fire($e->getCode(), $request, $response, $e);

        // not caught
        if(!$caught) {
            throw $e;
        }

        return $response;
    }


    /**
     * Catch other error
     * @param \exception $e
     * @param Request $request
     * @throws \exception
     * @return string
     */
    protected function error(\exception $e, Request $request)
    {
        // create response
        $response = new Response;

        // log error
        $class = get_class($e);
        Logger::critical($class . ' : ' . $e->getMessage());

        // specific & general error
        $caught = $this->fire('kernel.error.' . $class, $request, $response, $e);
        $caught += $this->fire('kernel.error', $request, $response, $e);

        // not caught
        if(!$caught) {
            throw $e;
        }

        return $response;
    }


    /**
     * Emulate url request
     * @param $query
     * @return Response
     */
    public function to($query)
    {
        $request = new Request($query);
        return $this->handle($request);
    }


    /**
     * 404 Not found
     * @param string $to
     * @return $this
     */
    public function lost($to)
    {
        $this->on(404, function() use($to) {
            Logger::info('404 Not found ! Go to ' . $to);
            $this->to($to);
        });

        return $this;
    }


    /**
     * 403 Forbidden
     * @param string $to
     * @return $this
     */
    public function nope($to)
    {
        $this->on(403, function() use($to) {
            Logger::info('403 Forbidden ! Go to ' . $to);
            $this->to($to);
        });

        return $this;
    }

}