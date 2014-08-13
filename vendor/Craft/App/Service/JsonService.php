<?php

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
                $response->format = 'application/json';
                $response->content = json_encode($response->data, JSON_PRETTY_PRINT);
                Logger::info('App.Json : render response as json');
            }

        }

        return $response;
    }

}