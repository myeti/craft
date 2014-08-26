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

    /** @var bool */
    protected $running = false;


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
        Logger::info('Service ' . get_class($service) . ' plugged');

        return $this;
    }


    /**
     * Handle context request
     * @param Request $request
     * @param Response $response
     * @throws \Exception
     * @return bool
     */
    public function handle(Request $request = null, Response $response = null)
    {
        // kernel is now running
        Logger::info('Kernel ' . ($this->running ? 'restart' : 'start'));
        if(!$this->running) {
            $this->running = true;
        }

        // safe
        try {

            // start event
            $this->fire('kernel.start');

            // create default request
            if(!$request) {
                $request = new Request;
            }

            // create default response
            if(!$response) {
                $response = new Response;
            }

            // request event
            $this->fire('kernel.request', $request);
            if(!$request->alive) {
                return false;
            }

            // execute request
            parent::handle($request, $response);
            Logger::info('Request executed');

            // response event
            $this->fire('kernel.response', $request, $response);
            if(!$request->alive) {
                return false;
            }

        }
        // internal error (404, 403 etc...)
        catch(Error\Internal $e) {

            // dispatch as http event or inject as exception
            Logger::error('Internal : ' . $e->getCode() . ' ' . $e->getMessage());
            if(!$this->fire($e->getCode(), $request, $response, $e)) {
                throw $e;
            }

            // dead
            if(!$request->alive) {
                return false;
            }

        }
        // other errors
        catch(\Exception $e) {

            // dispatch as specific error
            Logger::error(get_class($e) . ' : ' . $e->getMessage());
            $event = 'kernel.error.' . get_class($e);
            $caught = $this->fire($event, $request, $response, $e);

            // dispatch as general error
            $caught += $this->fire('kernel.error', $request, $response, $e);

            // no listener, throw it again...
            if(!$caught) {
                throw $e;
            }

            // dead
            if(!$request->alive) {
                return false;
            }

        }

        // send response
        if($response instanceof Response) {
            $response->send();
            Logger::info('Response sent with code ' . $response->code);
        }
        else {
            Logger::info('No response sent');
        }

        // end event
        $this->fire('kernel.end', $request, $response);

        // end
        $this->running = false;
        Logger::info('Kernel end');

        return true;
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
        $this->on(404, function(Request $request) use($to) {
            Logger::info('404 Not found ! Go to ' . $to);
            $request->halt();
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
        $this->on(403, function(Request $request) use($to) {
            Logger::info('403 Forbidden ! Go to ' . $to);
            $request->halt();
            $this->to($to);
        });

        return $this;
    }

}