<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Forge;

use Craft\App\Kernel;
use Craft\App\Service\AuthService;
use Craft\App\Service\RenderService;
use Craft\App\Service\ResolverService;
use Craft\App\Service\RouterService;
use Craft\App\Service\WhoopsService;
use Craft\Map\RouterInterface;
use Craft\View\EngineInterface;

/**
 * Ready to use app
 */
class App extends Kernel
{

    /**
     * Ready-to-use app
     * @param RouterInterface $router
     * @param EngineInterface $engine
     */
    public function __construct(RouterInterface $router, EngineInterface $engine)
    {
        $this->plug(new RouterService($router));
        $this->plug(new ResolverService);
        $this->plug(new AuthService);
        $this->plug(new RenderService($engine));

        if(!Mog::env('prod')) {
            $this->plug(new WhoopsService);
        }
    }

}