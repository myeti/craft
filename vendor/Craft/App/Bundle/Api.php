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

use Craft\App\Bundle;
use Craft\App\Service;
use Craft\Map\Router;

/**
 * Ready to use app
 */
class Api extends Bundle
{

    /**
     * Ready-to-use API
     * @param array $classes
     */
    public function __construct(array $classes)
    {
        $router = Router::annotations($classes);
        $this->plug(new Service\RouterService($router));

        $this->plug(new Service\ResolverService);
        $this->plug(new Service\AuthService);

        $this->plug(new Service\JsonService);
    }

}