<?php

namespace Craft\Bundle;

use Craft\App\Layer;
use Craft\App\Layer\ActionRouter;
use Craft\App\Layer\Firewall;
use Craft\App\Layer\MetaParser;
use Craft\App\Layer\JsonEngine;

class WebService extends App
{

    /**
     * Set views root dir
     * @param string $dir
     */
    public function __construct($dir)
    {
        $this->plug(new ActionRouter($dir), Layer::ROUTER);
        $this->plug(new MetaParser);
        $this->plug(new Firewall, Layer::AUTH);
        $this->plug(new JsonEngine(), Layer::VIEW);
    }

}