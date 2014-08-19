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

use Craft\App\Kernel;
use Craft\App\Service;
use Craft\App\Service\AuthService;
use Craft\App\Service\ResolverService;
use Craft\App\Service\RouterService;
use Craft\App\Service\JsonService;
use Craft\Map\Router;

/**
 * Ready to use app
 */
class App extends Kernel
{

    /**
     * Ready-to-use API
     * @param array $classes
     */
    public function __construct(array $classes)
    {
        $router = Router::annotations($classes);
        $this->plug(new RouterService($router));

        $this->plug(new ResolverService);
        $this->plug(new AuthService);

        $this->plug(new JsonService);
    }

}