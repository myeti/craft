<?php

namespace craft;

class App
{

    use Events;

    /** @var Router */
    protected $_router;

    /** @var Builder */
    protected $_builder;

    /** @var Engine */
    protected $_engine;

    /**
     * Setup with components
     * @param Router $router
     * @param Builder $builder
     * @param View $view
     */
    public function __construct(Router $router, Builder $builder = null, Engine $engine = null)
    {
        $this->_router = $router;
        $this->_builder = $builder ?: new Builder();
        $this->_engine = $engine ?: new Engine();
    }

    /**
     * Main process
     */
    public function process($query = null)
    {
        // resolve query
        $query = $query ?: $_SERVER['QUERY_STRING'];

        // start process
        $this->fire('start', ['query' => &$query]);

        // route
        $route = $this->_router->find($query);
        $this->fire('route', ['route' => &$route]);

        // env data
        foreach($route->env as $key => $value){
            env($key, $value);
        }

        // 404
        if(!$route){
            $this->fire(404, ['route' => &$route]);
        }
        else {

            // resolve
            $build = $this->_builder->resolve($route->target);
            $this->fire('resolve', ['build' => &$build]);

            // 403
            if(isset($build->metadata['auth']) and $build->metadata['auth'] < 0){
                $this->fire(403, ['build' => &$build]);
            }
            else {

                // call
                $data = $this->_builder->call($build->action, $route->args);
                $this->fire('call', ['build' => &$build, 'data' => &$data]);

                // render
                $this->_engine->render($data, $build->metadata);
                $this->fire('render', ['build' => &$build, 'data' => &$data]);

            }

        }

        // end process
        $this->fire('end');
    }

}