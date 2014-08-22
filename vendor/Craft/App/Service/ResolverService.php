<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Service;

use Craft\App;
use Craft\Reflect\Action;
use Craft\Reflect\InjectorInterface;
use Craft\Log\Logger;

/**
 * Resolve action and read metadata.
 */
class ResolverService extends App\Service
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
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return ['kernel.request' => 'onKernelRequest'];
    }


    /**
     * Handle request
     * @param App\Request $request
     */
    public function onKernelRequest(App\Request $request)
    {
        // resolve
        $action = Action::resolve($request->action, $this->injector);

        // update request
        $request->action = $action->callable;
        $request->meta = array_merge($request->meta, $action->meta);

        Logger::info('App.Resolver : request action resolved');
    }

}