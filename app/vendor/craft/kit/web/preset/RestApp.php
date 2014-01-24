<?php

namespace craft\kit\web\preset;

use craft\kit\web\App;
use craft\kit\web\process\CallerHandler;
use craft\kit\web\process\FirewallHandler;
use craft\kit\web\process\ResolverHandler;
use craft\kit\web\process\RouterHandler;
use craft\kit\dispatcher\Dispatcher;

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
            new RouterHandler($routes),
            new ResolverHandler(),
            new FirewallHandler(),
            new CallerHandler()
        ];

        // setup dispatcher
        Dispatcher::__construct($handlers);
    }

} 