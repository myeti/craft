<?php

namespace Craft\App\Service;

use Craft\App\Request;
use Craft\App\Response;
use Craft\App\Service;
use Craft\Box\Auth;
use Craft\Box\Session;
use Craft\Box\Mog;
use Craft\Log\Logger;
use Whoops;

class WhoopsService extends Service
{

    /** @var string */
    public $name = 'Whoops.Handler';

    /** @var  */
    protected $whoops;


    /**
     * Setup Whoops
     */
    public function __construct()
    {
        $this->whoops = new Whoops\Run;
        $this->whoops->pushHandler(new Whoops\Handler\PrettyPageHandler);
        $this->whoops->register();
    }


    /**
     * Handle error
     * @param \Exception $e
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function error(\Exception $e, Request $request, Response $response = null)
    {
        // new handler
        $handler = new Whoops\Handler\PrettyPageHandler;

        // add data
        $handler->addDataTable('Craft Request', (array)$request);
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

        // create response
        if(!$response) {
            $response = new Response;
        }

        // process exception
        $response->content = $this->whoops->handleException($e);
        $response->code = $e->getCode() ?: 500;

        debug($response);
        return $response;
    }

} 