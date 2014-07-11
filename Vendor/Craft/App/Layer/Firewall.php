<?php

namespace Craft\App\Layer;

use Craft\App\Event\Forbidden;
use Craft\App\Layer;
use Craft\App\Request;
use Craft\Box\Auth;

/**
 * Check if user is allowed to execute
 * the requested action when @auth is specified.
 *
 * Needs Layer\Metadata
 */
class Firewall extends Layer
{

    /**
     * Handle request
     * @param Request $request
     * @throws \Craft\App\Event\Forbidden
     * @return Request
     */
    public function before(Request $request)
    {
        if(isset($request->meta['auth']) and Auth::rank() < $request->meta['auth']) {
            throw new Forbidden('User not allowed for query "' . $request->query . '"');
        }

        return $request;
    }

}