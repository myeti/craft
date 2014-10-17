<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App\Bundle;

use Craft\App;
use Craft\Box\Mog;
use Craft\Debug\Error\FileNotFound;
use Craft\Routing\UrlRouter;
use Craft\View\EngineInterface;

/**
 * Ready to use app
 */
class StaticApp extends App\Core
{

    /**
     * Ready-to-use static app
     * @param EngineInterface $engine
     */
    public function __construct(EngineInterface $engine)
    {
        $router = new UrlRouter([
            '/'        => function($request){ $request->meta['render'] = 'index'; },
            '/:render' => function($render, $request){ $request->meta['render'] = $render; }
        ]);

        // init kernel
        parent::__construct();

        // init built-in services
        $this->plug(new App\Service\Routing($router));
        $this->plug(new App\Service\Resolver);
        $this->plug(new App\Service\Rendering($engine));

        // error handling : dev mode only
        if(Mog::in('dev')) {
            $this->plug(new App\Service\Whoops);
        }
    }


    /**
     * Handle context request
     * @param App\Request $request
     * @param App\Response $response
     * @throws FileNotFound
     * @throws \Exception
     * @return bool
     */
    public function handle(App\Request $request = null, App\Response $response = null)
    {
        // try rendering
        try {
            return parent::handle($request, $response);
        }
        // catch template not found as 404
        catch(FileNotFound $e) {
            if(!$this->fire(404)) {
                throw $e;
            }
        }

        return false;
    }

}