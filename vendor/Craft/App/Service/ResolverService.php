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

use Craft\App\Service;
use Craft\App\Request;
use Craft\Reflect\Action;
use Craft\Reflect\InjectorInterface;
use Craft\Log\Logger;

/**
 * Resolve action and read metadata.
 */
class ResolverService extends Service
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