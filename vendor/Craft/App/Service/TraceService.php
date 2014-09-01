<?php

namespace Craft\App\Service;

use Craft\App;
use Craft\Trace\Panel;

class TraceService extends App\Service
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
     * End of execution
     * @param App\Request $request
     * @param App\Response $response
     */
    public function onKernelResponse(App\Request $request, App\Response $response)
    {
        $response->content .= Panel::render();
    }

}