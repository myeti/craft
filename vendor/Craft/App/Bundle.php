<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App;

use Craft\Box\Mog;
use Craft\Event;
use Craft\Map\RouterInterface;
use Craft\View\EngineInterface;

/**
 * Ready to use app
 */
class Bundle extends Kernel
{

    /**
     * Ready-to-use app
     * @param RouterInterface $router
     * @param EngineInterface $engine
     */
    public function __construct(RouterInterface $router, EngineInterface $engine)
    {
        // init kernel
        parent::__construct();

        // init built-in services
        $this->plug(new Service\RouterService($router));
        $this->plug(new Service\ResolverService);
        $this->plug(new Service\AuthService);
        $this->plug(new Service\RenderService($engine));

        // error handling : dev mode only
        if(Mog::in('dev')) {
            $this->plug(new Service\WhoopsService);
        }
    }

}