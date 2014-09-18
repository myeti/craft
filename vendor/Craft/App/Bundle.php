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
use Craft\Routing\RouterInterface;
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
        $this->plug(new Service\Routing($router));
        $this->plug(new Service\Resolver);
        $this->plug(new Service\Firewall);
        $this->plug(new Service\Rendering($engine));

        // error handling : dev mode only
        if(Mog::in('dev')) {
            $this->plug(new Service\Whoops);
        }
    }

}