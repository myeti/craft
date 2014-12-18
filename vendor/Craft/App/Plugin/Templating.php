<?php

namespace Craft\App\Plugin;

use Craft\App;
use Craft\View;

class Templating extends Rendering
{

    /** @var View\EngineInterface */
    protected $engine;


    /**
     * Embed html engine
     * @param View\EngineInterface $engine
     * @param string $resolution
     */
    public function __construct(View\EngineInterface $engine, $resolution = 'meta.render')
    {
        $this->engine = $engine;
        $this->resolution = $resolution;
    }


    /**
     * Get inner engine
     * @return View\EngineInterface
     */
    public function engine()
    {
        return $this->engine;
    }


    /**
     * Generate response content using template
     * @param App\Response $response
     * @param string $template
     * @return App\Response
     */
    public function generate($template, App\Response $response = null)
    {
        if(!$response) {
            $response = new App\Response;
        }

        $content = $this->engine->render($template, $response->content());
        $response->content($content);

        return $response;
    }


    /**
     * Handle response from meta @render
     * @param App\Response $response
     * @param App\Request $request
     */
    public function resolveMetaRender(App\Request $request, App\Response &$response)
    {
        // meta @render
        if($render = $request->action()->meta('render')) {

            // is html ?
            @list($format, $template) = explode(' ', $render);
            if($format == 'html') {
                $response = $this->generate($template, $response);
            }

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
        if($request->accept()->negociate('text/html')) {

            // meta @html
            if($template = $request->action()->meta('html')) {
                $response = $this->generate($template, $response);
            }

        }
    }
}