<?php

namespace Craft\App\Service;

use Craft\App;
use Craft\Box\Auth;
use Craft\Box\Session;
use Craft\Box\Mog;
use Craft\Debug\Logger;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

class Whoops extends App\Service
{

    /** @var Run */
    protected $whoops;


    /**
     * Setup Whoops
     */
    public function __construct()
    {
        $this->whoops = new Run;
        $this->whoops->pushHandler(new PrettyPageHandler);
        $this->whoops->allowQuit(false);
        $this->whoops->register();
    }


    /**
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return ['kernel.error' => 'onKernelError'];
    }


    /**
     * Handle error
     * @param App\Request $request
     * @param App\Response $response
     * @param \Exception $e
     */
    public function onKernelError(App\Request $request, App\Response $response, \Exception $e)
    {
        // hide
        $this->whoops->writeToOutput(false);

        // new handler
        $handler = new PrettyPageHandler;

        // add data
        $handler->addDataTable('Craft Request', get_object_vars($request));
        $handler->addDataTable('Craft Response', get_object_vars($response));
        $handler->addDataTable('Craft Session', Session::all());
        $handler->addDataTable('Craft Auth', [
            'rank' => Auth::rank(),
            'user' => Auth::user(),
        ]);
        $handler->addDataTable('Craft Mog', get_object_vars(Mog::context()));

        // push handler
        $this->whoops->clearHandlers();
        $this->whoops->pushHandler($handler);

        // process exception
        $response->content = $this->whoops->handleException($e);
        $response->code = $e->getCode() ?: 500;

        // show
        $this->whoops->writeToOutput(true);
    }

}