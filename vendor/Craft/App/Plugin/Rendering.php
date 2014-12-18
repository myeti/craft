<?php

namespace Craft\App\Plugin;

use Craft\App;
use Craft\View;

/**
 * Handle response rendering
 */
abstract class Rendering extends App\Plugin
{

    /** @var array */
    protected $resolutions = [
        'meta.render'   => 'resolveMetaRender',
        'header.accept' => 'resolveHeaderAccept',
    ];

    /** @var string */
    protected $resolution = 'meta.render';


    /**
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return ['kernel.response' => 'onKernelResponse'];
    }

    /**
     * Handle response
     * @param App\Response $response
     * @param App\Request $request
     */
    public function onKernelResponse(App\Request $request, App\Response &$response = null)
    {
        if(isset($this->resolutions[$this->resolution])) {
            $method = $this->resolutions[$this->resolution];
            return $this->{$method}($request, $response);
        }

        throw new \LogicException('Could not find "' . $this->resolution . '" resolution');
    }

}