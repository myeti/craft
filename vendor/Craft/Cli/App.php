<?php

namespace Craft\Cli;

use Craft\App\Kernel;
use Craft\Router;

class App extends Kernel
{

    /**
     * Init with commands
     * @param array $commands
     */
    public function __construct(array $commands)
    {
        $router = new Router\Basic($commands);
        $handler = new App\Handler($router);

        parent::__construct($handler);
    }

} 