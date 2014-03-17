<?php

namespace Craft\App\Plugin;

use Craft\App\Plugin;
use Craft\App\Request;
use Craft\Reflect\Resolver;

class Metadata extends Plugin
{

    /**
     * Handle request
     * @param Request $request
     * @return Request
     */
    public function before(Request $request)
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