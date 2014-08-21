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

use Craft\App\Error\Forbidden;
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

    /**
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return ['kernel.request' => 'onKernelRequest'];
    }


    /**
     * Handle request
     * @param Request $request
     * @throws Forbidden
     * @return Request
     */
    public function onKernelRequest(Request $request)
    {
        // default value
        if(!isset($request->meta['auth'])) {
            $request->meta['auth'] = 0;
        }

        // attempt
        if(!Auth::rank($request->meta['auth'])) {
            throw new Forbidden('User not allowed for query "' . $request->query . '"');
        }

        Logger::info('App.Firewall : user is allowed');

        return $request;
    }

}