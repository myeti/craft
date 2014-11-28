<?php

namespace Craft\App\Service;

use Craft\App;
use Craft\Debug\Error;
use Craft\Debug\Logger;
use Craft\Orm\Syn;
use Craft\Router;
use Craft\View;
use Craft\Kit\Action;

/**
 * Handle url routing, param mapping and html rendering
 */
class Web extends App\Service
{

    /** @var Router\Seeker */
    protected $router;

    /** @var View\Renderer */
    protected $engine;

    /** @var callable[] */
    protected $mappers = [];


    /**
     * Init HttpHandler
     * @param Router\Seeker $router
     * @param View\Renderer $engine
     */
    public function __construct(Router\Seeker $router, View\Renderer $engine)
    {
        $this->router = $router;
        $this->engine = $engine;
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
     * Get listening methods
     * @return array
     */
    public function register()
    {
        return [
            'kernel.request' => 'onKernelRequest',
            'kernel.response' => 'onKernelResponse'
        ];
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
        $route = $this->router->find($request->url()->query);

        // 404
        if(!$route) {
            throw new App\Internal\NotFound('Route ' . $request->url()->query . ' not found');
        }

        // resolve action
        $action = Action::resolve($route->action);

        // clean args
        $args = [];
        $params = $action->ref->getParameters();
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
                        ? call_user_func_array($this->mappers[$classname], [$request])
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


    /**
     * Handle response
     * @param App\Response $response
     * @param App\Request $request
     */
    public function onKernelResponse(App\Request $request, App\Response &$response = null)
    {
        // get meta
        $meta = $request->action()->meta;

        // render template on demand
        if(!empty($meta['render'])) {
            $content = $this->engine->render($meta['render'], $response->content());
            $response->content($content);
        }
        // render json if async request
        elseif(isset($meta['json']) and $meta['json'] == 'async' and Mog::ajax()) {
            $response = App\Response::json($response->content());
        }
        // render json on demand
        elseif(isset($meta['json']) and !$meta['json']) {
            $response = App\Response::json($response->content());
        }
    }

}