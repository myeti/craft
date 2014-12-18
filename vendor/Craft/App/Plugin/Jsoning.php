<?php

namespace Craft\App\Plugin;

use Craft\App;
use Craft\View;

class Jsoning extends Rendering
{

    /** @var int */
    protected $options = 0;


    /**
     * Embed json options
     * @param int $options
     * @param string $resolution
     */
    public function __construct($options = JSON_PRETTY_PRINT, $resolution = 'meta.render')
    {
        $this->options = $options;
        $this->resolution = $resolution;
    }


    /**
     * Handle response from meta @render
     * @param App\Response $response
     * @param App\Request $request
     */
    public function resolveMetaRender(App\Request $request, App\Response &$response)
    {
        // meta @render is json
        if($request->action()->meta('render') == 'json') {

            // serialize to json format
            $json = json_encode($response->content(), $this->options);
            $response->content($json);
            $response->format('application/json');
        }
    }


    /**
     * Handle response from header HTTP_ACCEPT
     * @param App\Response $response
     * @param App\Request $request
     */
    public function resolveHeaderAccept(App\Request $request, App\Response &$response)
    {
        // head accept
        if($request->accept()->negociate('application/json')) {

            // serialize to json format
            $json = json_encode($response->content(), $this->options);
            $response->content($json);
            $response->format('application/json');
        }
    }
}