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

use Craft\Router;
use Craft\View;
use Craft\Box\Mog;

/**
 * Ready to use app
 */
class Web extends Kernel
{

    /**
     * Ready-to-use app
     * @param Router\Seeker $router
     * @param View\Renderer $engine
     */
    public function __construct(Router\Seeker $router, View\Renderer $engine)
    {
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


    /**
     * Auto-resolve request query
     * @return bool
     */
    public function run($query = null)
    {
        $query = $query ?: Mog::query();
        return parent::run($query);
    }

}