<?php

namespace Craft\App;

use Craft\Error\Abort;
use Craft\Reflect\Event;

class Kernel extends Dispatcher
{

    use Event;

    /** @var Plugin[] */
    protected $plugins = [];


    /**
     * Add plugin
     * @param Plugin $plugin
     * @return $this
     */
    public function plug(Plugin $plugin)
    {
        $this->plugins[] = $plugin;

        return $this;
    }


    /**
     * Handle context request
     * @param Request $request
     * @throws \Craft\Error\Abort
     * @throws \Exception
     * @return Response
     */
    public function handle(Request $request = null)
    {
        // resolve request
        if(!$request) {
            $request = Request::generate();
        }

        // safe
        try {

            // execute 'before' plugin
            foreach($this->plugins as $before) {
                $request = $before->before($request);
            }

            // dispatch
            $response = parent::handle($request);

            // execute 'after' plugin
            foreach($this->plugins as $after) {
                $response = $after->after($request, $response);
            }

        }
        // abort
        catch(Abort $e) {

            // error as event
            $done = $this->fire($e->getCode(), [$request, $e->getMessage()]);
            if(!$done) {
                throw $e;
            }

            return false;
        }

        // send response
        echo $response;

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

}