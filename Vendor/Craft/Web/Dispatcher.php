<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Web;

use Craft\Reflect\Resolver;

class Dispatcher implements Handler
{

    /** @var Resolver */
    protected $resolver;


    /**
     * Setup dispatcher
     * @param Resolver $resolver
     */
    public function __construct(Resolver $resolver = null)
    {
        $this->resolver = $resolver;
    }


    /**
     * Run dispatcher on request
     * @param Request $request
     * @throws \BadMethodCallException
     * @return Response
     */
    public function handle(Request $request)
    {
        // apply resolver
        if($this->resolver) {
            $action = $this->resolver->resolve($request->action);
            $request->action = $action->callable;
            $request->meta = $action->metadata;
        }

        // not a valid callable
        if(!is_callable($request->action)) {
            throw new \BadMethodCallException('Request::action must be a valid callable.');
        }

        // run
        $data = call_user_func_array($request->action, $request->args);

        // create response
        $response = new Response();
        $response->data = $data;

        return [$request, $response];
    }

}

