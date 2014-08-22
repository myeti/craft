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
use Craft\View;
use Craft\Log\Logger;

/**
 * Render view using the html engine
 * when @render in specified.
 *
 * Needs Service\ResolverService
 */
class RenderService extends App\Service
{

    /** @var View\EngineInterface */
    protected $engine;


    /**
     * Setup engine
     * @param View\EngineInterface $engine
     */
    public function __construct(View\EngineInterface $engine)
    {
        $this->engine = $engine;
    }


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
        // render if metadata provided
        if(!empty($request->meta['render'])) {
            $response->content = $this->engine->render($request->meta['render'], $response->data);
            Logger::info('App.Html : render template "' . $request->meta['render'] . '" as html');
        }
    }

}