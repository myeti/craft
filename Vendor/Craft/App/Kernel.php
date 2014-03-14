<?php

namespace Craft\App;

use Craft\App\Handler\Before;
use Craft\App\Handler\After;
use Craft\Error\Abort;
use Craft\Reflect\Event;

class Kernel extends Dispatcher
{

    use Event;

    /** @var Before[] */
    protected $before = [];

    /** @var After[] */
    protected $after = [];


    /**
     * Add plugin
     * @param Before $plugin
     * @return $this
     */
    public function plug(Before $plugin)
    {
        if($plugin instanceof After) {
            $this->after[] = $plugin;
        }
        else {
            $this->before[] = $plugin;
        }

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
            foreach($this->before as $before) {
                $request = $before->handle($request);
            }

            // dispatch
            $response = parent::handle($request);

            // execute 'after' plugin
            foreach($this->after as $after) {
                $response = $after->handle($request, $response);
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