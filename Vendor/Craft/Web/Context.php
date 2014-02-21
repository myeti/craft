<?php

namespace Craft\Web;

use Craft\Router\Route;
use Craft\Reflect\Action;

class Context
{

    /** @var Request */
    public $request;

    /** @var Route */
    public $route;

    /** @var Action */
    public $action;

    /**
     * @param Request $request
     * @param Route $route
     * @param Action $action
     */
    public function __construct(Request $request, Route $route = null, Action $action = null)
    {
        $this->request = $request;
        $this->route = $route;
        $this->action = $action;
    }

}