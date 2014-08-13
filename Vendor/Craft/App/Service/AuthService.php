<?php

namespace Craft\App\Service;

use Craft\Error\Forbidden;
use Craft\App\Service;
use Craft\App\Request;
use Craft\Log\Logger;
use Craft\Box\Auth;

/**
 * Check if user is allowed to execute
 * the requested action when @auth is specified.
 *
 * Needs Service\ResolverService
 */
class AuthService extends Service
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
     * @throws Forbidden
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