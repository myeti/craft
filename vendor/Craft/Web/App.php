<?php

namespace Craft\Web;

use Craft\App\Kernel;
use Craft\App\Service;
use Craft\Router;
use Craft\View;

class App extends Kernel
{

    /**
     * Init web app with router and html engine
     * @param Router\Seeker $router
     * @param View\Renderer $engine
     * App\Service ...$services
     */
    public function __construct(Router\Seeker $router, View\Renderer $engine, Service ...$services)
    {
        $handler = new App\Handler($router, $engine);

        parent::__construct($handler, ...$services);
    }

} 