<?php

namespace Craft\App\Preset;

use Craft\App;
use Craft\App\Dispatcher;
use Craft\App\Handler\Caller;
use Craft\App\Handler\Firewall;
use Craft\App\Handler\Resolver;
use Craft\App\Handler\Router;

class RestApp extends App
{

    /**
     * Setup router & handlers without no presenter
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        // define handlers
        $handlers = [
            new Router($routes),
            new Resolver(),
            new Firewall(),
            new Caller()
        ];

        // setup dispatcher
        Dispatcher::__construct($handlers);
    }

} 