<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Web\Kernel;

use Craft\Web\Handler;
use Craft\Web\Request;
use Craft\Web\Response;
use Craft\Reflect\Resolver;

class Reflector implements Handler
{

    /** @var Handler */
    protected $handler;

    /** @var Resolver */
    protected $resolver;


    /**
     * Setup dispatcher
     * @param Handler $handler
     * @param Resolver $resolver
     */
    public function __construct(Handler $handler, Resolver $resolver)
    {
        $this->handler = $handler;
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
        $action = $this->resolver->resolve($request->action);
        $request->action = $action->callable;
        $request->meta = $action->metadata;

        // run inner handler
        return $this->handler->handle($request);
    }

} 