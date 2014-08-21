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
use Craft\Event\EventInterface;
use Craft\Event\Subject;
use Craft\Log\Logger;

/**
 * Advanced Dispatcher :
 * manages inner events
 * and plug services
 */
class Kernel extends Dispatcher implements EventInterface
{

    use Subject;

    /** @var bool */
    protected $running = false;


    /**
     * Add service
     * @param Service $service
     * @return $this
     */
    public function plug(Service $service)
    {
        // resolve name
        $name = get_class($service);

        // register service
        $service->listen($this);
        Logger::info('App.Kernel : service "' . $name . '" plugged');

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
        $this->running = true;
        Logger::info('App.Kernel : start');

        // safe
        try {

            // start event
            Logger::info('App.Kernel : start event');
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
            Logger::info('App.Kernel : request event');
            $this->fire('kernel.request', $request);

            // execute request
            $response = parent::handle($request, $response);
            Logger::info('App.Kernel : request executed');

            // request event
            Logger::info('App.Kernel : response event');
            $this->fire('kernel.response', $request, $response);

        }
        // internal error (404, 403 etc...)
        catch(Error\Internal $e) {

            // dispatch as http event or inject as exception
            Logger::error('App.Kernel : internal error ' . $e->getCode() . ' : ' . $e->getMessage());
            if(!$this->fire($e->getCode(), $request, $response, $e)) {
                throw $e;
            }

        }
        // other errors
        catch(\Exception $e) {

            // dispatch as specific error
            Logger::error('App.Kernel : error ' . get_class($e) . ' : ' . $e->getMessage());
            $event = 'kernel.error.' . get_class($e);
            $caught = $this->fire($event, $request, $response, $e);

            // dispatch as general error
            $caught += $this->fire('kernel.error', $request, $response, $e);

            // no listener, throw it again...
            if(!$caught) {
                throw $e;
            }

        }

        // send response
        if($response instanceof Response) {
            $response->send();
            Logger::info('App.Kernel : response sent with code ' . $response->code);
        }
        else {
            Logger::info('App.Kernel : no response sent');
        }

        // end event
        Logger::info('App.Kernel : end event');
        $this->fire('kernel.end', $request, $response);

        // end
        $this->running = false;
        Logger::info('App.Kernel : end');

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
        $this->on(404, function() use($to) {
            Logger::info('App.Kernel : 404, redirect to "' . $to . '"');
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
            Logger::info('App.Kernel : 403, redirect to "' . $to . '"');
            $this->to($to);
        });

        return $this;
    }

}