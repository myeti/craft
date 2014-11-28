<?php

namespace Craft\App\Service;

use Craft\App;
use Craft\Box\Auth;
use Craft\Box\Session;
use Craft\Box\Mog;
use Craft\Debug\Logger;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

class Debugger extends App\Service
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
//        dd($request->error());

        // add data
        $handler->addDataTable('Craft/Url', (array)$request->url());
        $handler->addDataTable('Craft/Route', (array)$request->route());
        $handler->addDataTable('Craft/Action', (array)$request->action());
        $handler->addDataTable('Craft/Error', (array)$request->error());
        $handler->addDataTable('Craft/Response', (array)$response);
        $handler->addDataTable('Craft/Session', Session::all());
        $handler->addDataTable('Craft/Auth', [
            'rank' => Auth::rank(),
            'user' => Auth::user(),
        ]);

        // push handler
        $this->whoops->clearHandlers();
        $this->whoops->pushHandler($handler);

        // process exception
        $response->content($this->whoops->handleException($e));
        $response->code($e->getCode() ?: 418);

        // show
        $this->whoops->writeToOutput(true);
    }

}