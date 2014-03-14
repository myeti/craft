<?php

namespace Craft\App\Plugin;

use Craft\App\Handler\After;
use Craft\App\Request;
use Craft\App\Response;
use Craft\Box\Mog;
use Craft\View\Engine;
use Craft\View\Helper\Asset;
use Craft\View\Helper\Html;

class Templates extends After
{

    /** @var Engine */
    protected $engine;


    /**
     * Setup engine
     * @param string $root
     */
    public function __construct($root = null)
    {
        if(!$root) {
            $root = Mog::path();
        }

        $this->engine = new Engine($root);
        $this->engine->mount(new Html);
        $this->engine->mount(new Asset(Mog::base()));
    }


    /**
     * Handle response
     * @param Response $response
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request, Response $response = null)
    {
        // render if metadata provided
        if(!empty($request->meta['render'])) {
            $response->content = $this->engine->render($request->meta['render'], $response->data);
        }

        return $response;
    }

}