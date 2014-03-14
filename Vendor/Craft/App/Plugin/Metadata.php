<?php

namespace Craft\App\Plugin;

use Craft\App\Handler\Before;
use Craft\App\Request;
use Craft\Reflect\Resolver;

class Metadata extends Before
{

    /**
     * Handle request
     * @param Request $request
     * @return Request
     */
    public function handle(Request $request)
    {
        // get resolver
        $resolver = new Resolver();

        // get metadata
        $action = $resolver->resolve($request->action);
        $request->action = $action->callable;
        $request->meta = $action->meta;

        return $request;
    }

}