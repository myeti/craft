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

use Craft\Error\Abort;
use Craft\Event\Subject;
use Craft\Log\Logger;

/**
 * Advanced Dispatcher :
 * manages inner events
 * and plug layers.
 */
class Kernel extends Dispatcher
{

    use Subject;

    /** @var Service[] */
    protected $services = [];

    /** @var bool */
    protected $running = false;


    /**
     * Add service
     * @param Service $service
     * @throws \InvalidArgumentException
     * @return $this
     */
    public function plug(Service $service)
    {
        // resolve name
        $name = $service->name ?: get_class($service);

        // push service
        $this->services[$name] = $service;
        Logger::info('App.Kernel : service "' . $name . '" plugged');

        return $this;
    }


    /**
     * Drop service
     * @param string $name
     * @return $this
     */
    public function drop($name)
    {
        if(isset($this->services[$name])) {
            unset($this->services[$name]);
            Logger::info('App.Kernel : service "' . $name . '" unplugged');
        }

        return $this;
    }


    /**
     * Get inner service
     * @param string $name
     * @return bool|Service
     */
    public function service($name)
    {
        return isset($this->services[$name])
            ? $this->services[$name]
            : false;
    }


    /**
     * Handle context request
     * @param Request $request
     * @param Response $response
     * @throws Abort
     * @throws \Exception
     * @return bool
     */
    public function handle(Request $request = null, Response $response = null)
    {
        Logger::info('App.Kernel : kernel ' . ($this->running ? 'restart' : 'start'));
        $this->running = true;

        // resolve request
        if(!$request) {
            $request = Request::generate();
        }

        // safe
        try {

            // execute 'before' services
            foreach($this->services as $before) {
                $return = $before->before($request);
                if($return instanceof Request) {
                    $request = $return;
                }
            }

            // dispatch
            $response = parent::handle($request, $response);

            // execute 'after' services
            foreach($this->services as $after) {
                $return = $after->after($request, $response);
                if($return instanceof Response) {
                    $response = $return;
                }
            }

            // send response
            $response->send();
            Logger::info('App.Kernel : response sent with code ' . $response->code);

            // finisher services
            foreach($this->services as $finish) {
                $finish->finish($request, $response);
            }

        }
        // error
        catch(\Exception $e) {

            Logger::error('App.Kernel : ' . $e->getCode() . ' ' . $e->getMessage());

            // dispatch to custom events
            $done = $this->fire($e->getCode(), [$request, $e->getMessage()]);

            // no custom events
            if(!$done) {

                // try error services
                $handled = false;
                foreach($this->services as $error) {

                    // proceed
                    $response = $error->error($e, $request, $response);

                    // response returned : stop
                    if($response instanceof Response) {
                        $response->send();
                        $handled = true;
                    }
                }

                // no error services
                if(!$handled) {
                    throw $e;
                }

            }

        }

        $this->running = false;
        Logger::info('App.Kernel : kernel end');

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