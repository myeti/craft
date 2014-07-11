<?php

namespace Craft\Bundle;

use Craft\App\Kernel;
use Craft\App\Layer;
use Craft\App\Layer\UrlRouter;
use Craft\App\Layer\Firewall;
use Craft\App\Layer\MetaParser;
use Craft\App\Layer\HtmlEngine;

class App extends Kernel
{

    /**
     * Set routes
     * @param array $routes
     * @param string $views
     */
    public function __construct(array $routes, $views = null)
    {
        $this->plug(new UrlRouter($routes), Layer::ROUTER);
        $this->plug(new MetaParser);
        $this->plug(new Firewall, Layer::AUTH);
        $this->plug(new HtmlEngine($views), Layer::VIEW);
    }


    /**
     * 404 Not found
     * @param string $to
     */
    public function oops($to)
    {
        $this->on('error.404', function() use($to) {
           $this->to($to);
        });
    }


    /**
     * 403 Forbidden
     * @param string $to
     */
    public function nope($to)
    {
        $this->on('error.403', function() use($to) {
           $this->to($to);
        });
    }

}