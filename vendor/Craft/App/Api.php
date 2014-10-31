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
     */
    public function __construct(Router\Seeker $router)
    {
        // init built-in services
        $this->plug(new Service\Routing($router));
        $this->plug(new Service\Resolving);
        $this->plug(new Service\Firewall);
        $this->plug(new Service\Output('json'));
    }

}