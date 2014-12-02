<?php

namespace Craft\Web;

use Craft\App\Kernel;
use Craft\App\Service;
use Craft\Router;

class Api extends Kernel
{

    /**
     * Init web api with router
     * @param Router\Seeker $router
     * App\Service ...$services
     */
    public function __construct(Router\Seeker $router, Service ...$services)
    {
        $handler = new Api\Handler($router);

        parent::__construct($handler, ...$services);
    }

} 