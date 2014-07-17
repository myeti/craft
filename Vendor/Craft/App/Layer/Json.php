<?php

namespace Craft\App\Layer;

use Craft\App\Layer;
use Craft\App\Request;
use Craft\App\Response;
use Craft\Trace\Logger;
use Forge\Mog;

/**
 * Render data as json.
 */
class Json extends Layer
{

    /**
     * Render data as json
     * @param Request $request
     * @param Response $response
     * @return Response|void
     */
    public function after(Request $request, Response $response)
    {
        Logger::info('App : json rendering layer');

        // json output requested
        if(isset($request->meta['json'])) {

            // always or async
            if($request->meta['json'] != 'async' xor ($request->meta['json'] == 'async' and Mog::async())) {
                $response->format = 'application/json';
                $response->content = json_encode($response->data, JSON_PRETTY_PRINT);
                Logger::info('App : render response as json');
            }

        }

        return $response;
    }

}