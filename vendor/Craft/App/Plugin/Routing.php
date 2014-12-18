<?php

namespace Craft\App\Plugin;

use Craft\App;
use Craft\Debug\Error;
use Craft\Kit\Runnable;
use Craft\Orm\Syn;
use Craft\Router;
use Craft\View;

/**
 * Handle url routing, param mapping and html rendering
 */
class Routing extends App\Plugin
{

    /** @var Router\Seeker[] */
    protected $routers = [];

    /** @var callable[] */
    protected $mappers = [];


    /**
     * Init Router Handler
     * @param Router\Seeker $routers
     */
    public function __construct(Router\Seeker ...$routers)
    {
        $this->routers = $routers;
    }


    /**
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return ['kernel.request' => 'onKernelRequest'];
    }


    /**
     * Define model mapper
     * @param $model
     * @param callable $seeker
     * @return $this
     */
    public function map($model, callable $seeker)
    {
        $this->mappers[$model] = $seeker;
        return $this;
    }


    /**
     * Handle request
     * @param App\Request $request
     * @throws App\Internal\NotFound
     * @throws Error\ClassNotFound
     */
    public function onKernelRequest(App\Request $request)
    {
        // find route from query
        $route = null;
        foreach($this->routers as $router) {
            if($route = $router->find($request->url()->query)) {
                break;
            }
        }

        // 404
        if(!$route) {
            throw new App\Internal\NotFound('Route ' . $request->url()->query . ' not found');
        }

        // resolve action
        $action = new Runnable($route->action);

        // clean args
        $args = [];
        $params = $action->reflector->getParameters();
        foreach($params as $key => $param) {

            // map action arg names with route arg values
            $args[$key] = isset($route->args[$param->getName()])
                ? $route->args[$param->getName()]
                : null;

            // object mapping
            if($param->getClass()) {

                // get classname
                $classname = $param->getClass()->getName();

                // request object
                if($classname === App\Request::class) {
                    $args[$key] = &$request;
                }

                // model object
                elseif(Syn::mapper()->model($classname, true)) {

                    // get entity
                    $entity = isset($this->mappers[$classname])
                        ? call_user_func($this->mappers[$classname], $request)
                        : Syn::one($classname, ['id' => $args[$key]]);

                    // model not found
                    if(!$entity) {
                        throw new App\Internal\NotFound('Model ' . $classname . ' for mapping not found');
                    }

                    // map as arg
                    $args[$key] = $entity;
                }

            }

        }

        // update request
        $action->args = $args;
        $request->action($action);
        $request->route($route);
    }

}