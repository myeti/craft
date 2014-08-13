<?php

namespace Craft\App\Service;

use Craft\App\Service;
use Craft\App\Request;
use Craft\App\Response;
use Craft\Log\Logger;
use Craft\View\Engine;
use Craft\View\EngineInterface;

/**
 * Render view using the html engine
 * when @render in specified.
 *
 * Needs Service\ResolverService
 */
class RenderService extends Service
{

    /** @var Engine */
    protected $engine;


    /**
     * Setup engine
     * @param EngineInterface $engine
     */
    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }


    /**
     * Set views dir
     */
    public function dir($dir)
    {
        $this->engine->dir($dir);
    }


    /**
     * Handle response
     * @param Response $response
     * @param Request $request
     * @return Response
     */
    public function after(Request $request, Response $response = null)
    {
        // render if metadata provided
        if(!empty($request->meta['render'])) {
            $response->content = $this->engine->render($request->meta['render'], $response->data);
            Logger::info('App.Html : render template "' . $request->meta['render'] . '" as html');
        }

        return $response;
    }

}