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
use Craft\App\Service\RenderService;
use Craft\App\Service\ResolverService;
use Craft\App\Service\RouterService;
use Craft\Box\Mog;
use Craft\Map\Router;
use Craft\View\Engine;

/**
 * Ready to use app
 */
class App extends Kernel
{

    /**
     * Ready-to-use static app
     * @param array $routes
     * @param string $templates
     * @param string $assets
     */
    public function __construct(array $routes = [], $templates = null, $assets = '/')
    {
        $router = Router::files($templates, function(){});
        $this->plug(new RouterService($router));

        $this->plug(new ResolverService);

        $engine = new Engine($templates ?: Mog::path(), Mog::base() . $assets);
        $this->plug(new RenderService($engine));
    }


}