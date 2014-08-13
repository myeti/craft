<?php

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
     * Init app with classes
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