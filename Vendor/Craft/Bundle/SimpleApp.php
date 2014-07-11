<?php

namespace Craft\Bundle;

use Craft\App\Kernel;
use Craft\App\Layer;
use Craft\App\Layer\FileRouter;
use Craft\App\Layer\MetaParser;
use Craft\App\Layer\HtmlEngine;

class SimpleApp extends Kernel
{

    /**
     * Set views root dir
     * @param string $dir
     */
    public function __construct($dir)
    {
        $this->plug(new FileRouter($dir), Layer::ROUTER);
        $this->plug(new MetaParser);
        $this->plug(new HtmlEngine, Layer::VIEW);
    }

}