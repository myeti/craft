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

/**
 * Ready to use app
 */
class Api extends Web
{

    /**
     * Ready-to-use app
     * @param Router\Seeker $router
     * @param View\Renderer $engine
     */
    public function __construct(Router\Seeker $router, View\Renderer $engine = null)
    {
        // init built-in services
        $this->plug(new Service\Routing($router));
        $this->plug(new Service\Resolver);
        $this->plug(new Service\Firewall);

        // output
        $renderer = $engine
            ? new Service\Rendering($engine)
            : new Service\JsonOutput;

        $this->plug($renderer);
    }

}