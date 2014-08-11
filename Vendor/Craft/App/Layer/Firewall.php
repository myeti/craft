<?php

namespace Craft\App\Layer;

use Craft\Error\Forbidden;
use Craft\App\Layer;
use Craft\App\Request;
use Forge\Logger;
use Forge\Auth;

/**
 * Check if user is allowed to execute
 * the requested action when @auth is specified.
 *
 * Needs Layer\Metadata
 */
class Firewall extends Layer
{

    /** @var callable */
    protected $strategy;

    /**
     * Set firewall strategy
     * @param callable $strategy
     */
    public function strategy(callable $strategy)
    {
        $this->strategy = $strategy;
    }


    /**
     * Basic strategy
     * @param Request $request
     * @return bool
     */
    protected function basic(Request $request)
    {
        // check rank
        if(Auth::rank($request->meta['auth'])) {
            Auth::login(1);
            return true;
        }

        return false;
    }

    /**
     * Handle request
     * @param Request $request
     * @throws \Craft\Error\Forbidden
     * @return Request
     */
    public function before(Request $request)
    {
        // default value
        if(!isset($request->meta['auth'])) {
            $request->meta['auth'] = 0;
        }

        // strategy callable
        $attempt = $this->strategy ?: [$this, 'basic'];

        // attempt
        if(!$attempt) {
            throw new Forbidden('User not allowed for query "' . $request->query . '"');
        }

        Logger::info('App.Firewall : user is allowed');

        return $request;
    }

}