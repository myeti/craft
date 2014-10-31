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
use Craft\Box\Mog;
use Craft\View;
use Craft\Debug\Logger;

/**
 * Render view using the html engine
 * when @render in specified.
 *
 * Needs Service\RequestResolver
 */
class Templating extends App\Service
{

    /** @var View\Renderer */
    protected $engine;


    /**
     * Setup engine
     * @param View\Renderer $engine
     */
    public function __construct(View\Renderer $engine)
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
        // render template on demand
        if(!empty($request->meta['render'])) {
            $response->content = $this->engine->render($request->meta['render'], $response->data);
            Logger::info('Render template ' . $request->meta['render']);
        }
        // render json on async request
        elseif(isset($request->meta['json']) and $request->meta['json'] == 'async' and Mog::ajax()) {
            $response = App\Response::json($response->data);
            Logger::info('Render json over ajax request');
        }
        // render json on demand
        elseif(isset($request->meta['render']) and !$request->meta['json']) {
            $response = App\Response::json($response->data);
            Logger::info('Render json');
        }
    }

}