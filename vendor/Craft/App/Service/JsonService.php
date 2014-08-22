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

use Craft\App;
use Craft\Trace\Logger;
use Craft\Box\Mog;

/**
 * Render data as json.
 *
 * Needs Service\ResolverService
 */
class JsonService extends App\Service
{

    /**
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return ['kernel.response' => 'onKernelResponse'];
    }

    /**
     * Render data as json
     * @param App\Request $request
     * @param App\Response $response
     */
    public function onKernelResponse(App\Request $request, App\Response $response)
    {
        // json output requested
        if(isset($request->meta['json'])) {

            // always or async
            if($request->meta['json'] != 'async' xor ($request->meta['json'] == 'async' and Mog::async())) {
                $response = new App\Response\Json($response->data);
                Logger::info('Render response as json');
            }

        }
    }

}