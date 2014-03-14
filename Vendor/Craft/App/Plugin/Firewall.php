<?php

namespace Craft\App\Plugin;

use Craft\App\Event\Forbidden;
use Craft\App\Handler\Before;
use Craft\App\Request;
use Craft\Box\Auth;

class Firewall extends Before
{

    /**
     * Handle request
     * @param Request $request
     * @throws \Craft\App\Event\Forbidden
     * @return Request
     */
    public function handle(Request $request)
    {
        if(isset($request->meta['auth']) and Auth::rank() < $request->meta['auth']) {
            throw new Forbidden('User not allowed for query "' . $request->query . '"');
        }

        return $request;
    }

}