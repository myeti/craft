<?php

namespace Craft\App\Layer;

use Craft\App\Layer;
use Craft\App\Request;
use Craft\Reflect\Action;
use Craft\Reflect\InjectorInterface;
use Forge\Logger;

/**
 * Resolve action and read metadata.
 */
class Resolver extends Layer
{

    /** @var InjectorInterface */
    protected $injector;


    /**
     * Set injector
     * @param InjectorInterface $injector
     */
    public function __construct(InjectorInterface $injector = null)
    {
        $this->resolver = $injector;
    }


    /**
     * Handle request
     * @param Request $request
     * @return Request
     */
    public function before(Request $request)
    {
        // resolve
        $action = Action::resolve($request->action, $this->injector);

        // update request
        $request->action = $action->callable;
        $request->meta = array_merge($request->meta, $action->meta);

        Logger::info('App.Resolver : request action resolved');

        return $request;
    }

}