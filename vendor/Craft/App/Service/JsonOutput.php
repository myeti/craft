<?php

namespace Craft\App\Service;

use Craft\App;
use Craft\Debug\Logger;

class JsonOutput extends App\Service
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
     * Handle response
     * @param App\Response $response
     * @param App\Request $request
     */
    public function onKernelResponse(App\Request $request, App\Response $response = null)
    {
        $response = App\Response::json($response->data);
        Logger::info('Render json');
    }

} 