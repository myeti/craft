<?php

namespace Forge;

use Craft\App\Kernel;
use Craft\App\Service;
use Craft\App\Service\AuthService;
use Craft\App\Service\RenderService;
use Craft\App\Service\ResolverService;
use Craft\App\Service\RouterService;
use Craft\Map\Router;
use Craft\View\Engine;
use Craft\View\Helper\Markup;

/**
 * Ready to use app
 */
class App extends Kernel
{

    /**
     * Init app with routes and views dir
     * @param array $routes
     * @param string $views
     */
    public function __construct(array $routes = [], $views = null)
    {
        $router = new Router($routes);
        $this->plug(new RouterService($router));

        $this->plug(new ResolverService);
        $this->plug(new AuthService);

        $engine = new Engine($views ?: Mog::path());
        $engine->mount(new Markup(Mog::base()));
        $this->plug(new RenderService($engine));
    }

}