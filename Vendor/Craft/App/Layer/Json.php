<?php

namespace Craft\App\Layer;

use Craft\App\Layer;
use Craft\App\Request;
use Craft\App\Response;

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
        // format content to json
        if(isset($request->meta['json'])) {
            $response->format = 'application/json';
            $response->content = json_encode($response->data, JSON_PRETTY_PRINT);
        }

        return $response;
    }

} 