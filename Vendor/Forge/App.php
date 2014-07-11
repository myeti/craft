<?php

namespace Forge;

use Craft\App\Layer;

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
        $this->plug(new Routing($routes), Layer::ROUTER);
        $this->plug(new Metadata);
        $this->plug(new Firewall, Layer::AUTH);
        $this->plug(new Json);
        $this->plug(new Html($views), Layer::VIEW);
    }

}