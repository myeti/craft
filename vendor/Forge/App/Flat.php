<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Forge\App;

use Forge\App;
use Craft\App\Request;
use Craft\App\Response;
use Craft\Error\Abort;
use Craft\View\Error\TemplateNotFound;
use Craft\Map\Router;
use Craft\View\Engine;
use Craft\View\EngineInterface;

/**
 * Ready to use app
 */
class Flat extends App
{

    /**
     * Ready-to-use static app
     * @param EngineInterface $engine
     */
    public function __construct(EngineInterface $engine)
    {
        $router = new Router([
            '/'        => function($request){ $request->meta['render'] = 'index'; },
            '/:render' => function(){}
        ]);

        parent::__construct($router, $engine);
    }


    /**
     * Handle context request
     * @param Request $request
     * @param Response $response
     * @throws Abort
     * @throws \Exception
     * @return bool
     */
    public function handle(Request $request = null, Response $response = null)
    {
        // try rendering
        try {
            return parent::handle($request, $response);
        }
        // catch template not found as 404
        catch(TemplateNotFound $e) {
            if(!$this->fire(404)) {
                throw $e;
            }
        }

        return false;
    }

}