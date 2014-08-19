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

use Craft\App\Service;
use Craft\App\Request;
use Craft\App\Response;
use Craft\Log\Logger;
use Craft\Box\Mog;

/**
 * Render data as json.
 *
 * Needs Service\ResolverService
 */
class JsonService extends Service
{

    /** @var string */
    public $name = 'Render.Json';

    /**
     * Render data as json
     * @param Request $request
     * @param Response $response
     * @return Response|void
     */
    public function after(Request $request, Response $response)
    {
        // json output requested
        if(isset($request->meta['json'])) {

            // always or async
            if($request->meta['json'] != 'async' xor ($request->meta['json'] == 'async' and Mog::async())) {
                $response = new Response\Json($response->data);
                Logger::info('App.Json : render response as json');
            }

        }

        return $response;
    }

}