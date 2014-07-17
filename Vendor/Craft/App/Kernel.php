<?php

namespace Craft\App;

use Craft\Error\Abort;
use Craft\Pulse\Event;
use Craft\Trace\Logger;

/**
 * Advanced Dispatcher :
 * manages inner events
 * and plug layers.
 */
class Kernel extends Dispatcher
{

    use Event;

    /** @var Layer[] */
    protected $layers = [];


    /**
     * Add layer
     * @param Layer $layer
     * @param string $as
     * @return $this
     */
    public function plug(Layer $layer, $as = null)
    {
        // unique layer
        if($as) {
            $this->layers[$as] = $layer;
            Logger::info('App : layer "' . get_class($layer) . '" plugged as "' . $as . '".');
        }
        // anonymous layer
        else {
            $this->layers[] = $layer;
            Logger::info('App : layer "' . get_class($layer) . '" plugged.');
        }

        return $this;
    }


    /**
     * Handle context request
     * @param Request $request
     * @throws \Craft\Error\Abort
     * @throws \Exception
     * @return bool
     */
    public function handle(Request $request = null)
    {
        Logger::info('App : kernel start');

        // resolve request
        if(!$request) {
            $request = Request::generate();
        }

        // safe
        try {

            // execute 'before' layer
            foreach($this->layers as $before) {
                $return = $before->before($request);
                if($return instanceof Request) {
                    $request = $return;
                }
            }

            // dispatch
            $response = parent::handle($request);

            // execute 'after' layer
            foreach($this->layers as $after) {
                $return = $after->after($request, $response);
                if($return instanceof Response) {
                    $response = $return;
                }
            }

            // send response
            echo $response;
            Logger::info('App : response sent with code ' . $response->code);

            // finish process
            foreach($this->layers as $finish) {
                $finish->finish($request, $response);
            }

        }
        // abort
        catch(Abort $e) {

            Logger::error('App : ' . $e->getCode() . ' : ' . $e->getMessage());

            // error as event (if no listener registered, then raise error)
            $done = $this->fire($e->getCode(), [$request, $e->getMessage()]);
            if(!$done) {
                throw $e;
            }

            return false;
        }

        Logger::info('App : kernel end.');
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
            Logger::info('App : error 404, redirect to "' . $to . '"');
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
            Logger::info('App : error 403, redirect to "' . $to . '"');
            $this->to($to);
        });

        return $this;
    }

}