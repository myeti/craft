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
use Craft\View\Helper\Markup;

/**
 * Ready to use app
 */
class App extends Kernel
{

    /**
     * Init app with views dir
     * @param string $views
     */
    public function __construct($views = null)
    {
        $router = Router::files($views, function(){});
        $this->plug(new RouterService($router));

        $this->plug(new ResolverService);

        $engine = new Engine($views ?: Mog::path());
        $engine->mount(new Markup(Mog::base()));
        $this->plug(new RenderService($engine));
    }

}