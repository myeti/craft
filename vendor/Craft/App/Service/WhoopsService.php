<?php

namespace Craft\App\Service;

use Craft\App;
use Craft\Box\Auth;
use Craft\Box\Session;
use Craft\Box\Mog;
use Craft\Trace\Logger;
use Whoops;

class WhoopsService extends App\Service
{

    /** @var Whoops\Run */
    protected $whoops;


    /**
     * Setup Whoops
     */
    public function __construct()
    {
        $this->whoops = new Whoops\Run;
        $this->whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);
        $this->whoops->writeToOutput(false);
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
    public function onKernelError(App\Request $request, App\Response $response = null, \Exception $e)
    {
        // create response
        if(!$response) {
            $response = new App\Response;
            $response->code = $e->getCode() ?: 500;
        }

        // new handler
        $handler = new Whoops\Handler\PrettyPageHandler;

        // add data
        $handler->addDataTable('Craft Request', (array)$request);
        $handler->addDataTable('Craft Response', (array)$response);
        $handler->addDataTable('Craft Session', Session::all());
        $handler->addDataTable('Craft Auth', [
            'rank' => Auth::rank(),
            'user' => Auth::user(),
        ]);
        $handler->addDataTable('Craft Mog', [
            'url' => Mog::fullurl(),
            'url.host' => Mog::host(),
            'url.base' => Mog::base(),
            'url.query' => Mog::query(),
            'url.from' => Mog::from(),
            'http.code' => Mog::code(),
            'http.secure' => (int)Mog::https(),
            'http.method' => Mog::method(),
            'http.env' => Mog::env(),
            'http.browser' => Mog::browser(),
            'http.mobile' => (int)Mog::mobile(),
            'user.ip' => Mog::ip(),
            'user.local' => (int)Mog::local(),
            'time.elapsed' => Mog::elapsed(),
            'path' => Mog::path(),
            'kupo' => html_entity_decode(Mog::kupo()),
        ]);
        $handler->addDataTable('Craft Logs', explode("<br/>", Logger::logs()));

        // push handler
        $this->whoops->clearHandlers();
        $this->whoops->pushHandler($handler);

        // process exception
        $response->content = $this->whoops->handleException($e);
    }

}