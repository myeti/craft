<?php

namespace Craft\App\Layer;

use Craft\App\Layer;
use Craft\App\Request;
use Craft\Reflect\Resolver;
use Craft\Reflect\ResolverInterface;
use Craft\Trace\Logger;

/**
 * Resolve action and read metadata.
 */
class Metadata extends Layer
{

    /** @var ResolverInterface */
    protected $resolver;


    /**
     * Set resolver
     * @param ResolverInterface $resolver
     */
    public function __construct(ResolverInterface $resolver = null)
    {
        $this->resolver = $resolver ?: new Resolver;
    }


    /**
     * Handle request
     * @param Request $request
     * @return Request
     */
    public function before(Request $request)
    {
        Logger::info('App : metadata layer');

        $action = $this->resolver->resolve($request->action);
        $request->action = $action->callable;
        $request->meta = array_merge($request->meta, $action->meta);

        Logger::info('App : request metadata parsed');

        return $request;
    }

}