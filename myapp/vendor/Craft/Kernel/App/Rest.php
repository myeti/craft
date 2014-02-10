<?php

namespace Craft\Kernel\App;

use Craft\Kernel\App;
use Craft\View\Json;

class Rest extends App
{

    /** @var array */
    protected $config = [
        'json.wrapper'  => null
    ];

    /**
     * Render data as json
     */
    protected function createView()
    {
        return new Json($this->config['json.wrapper']);
    }

} 