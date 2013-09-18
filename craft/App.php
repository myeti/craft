<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 *
 * @author Aymeric Assier <aymeric.assier@gmail.com>
 * @date 2013-09-12
 * @version 0.1
 */
namespace craft;

class App
{

    use Events;

    /** @var Router */
    protected $_router;

    /** @var Builder */
    protected $_builder;


    /**
     * Setup with components
     * @param Router $router
     * @param Builder $builder
     */
    public function __construct(Router $router, Builder $builder = null)
    {
        $this->_router = $router;
        $this->_builder = $builder ?: new Builder();
    }


    /**
     * Main process
     */
    public function handle($query = null)
    {
        // resolve query
        if(!$query) {
            $query = isset($_SERVER['PATH_INFO'])
                ? $_SERVER['PATH_INFO']
                : '/';
        }

        // start process
        $this->fire('start', ['query' => &$query]);
        $this->_route($query);

        // end process
        $this->fire('end');
    }


    /**
     * Step 1 : routing
     * @param $query
     * @return bool|mixed
     */
    protected function _route($query)
    {
        // get query
        $query = '/' . ltrim($query, '/');

        // route
        $route = $this->_router->find($query);
        $this->fire('route', ['route' => &$route]);

        // 404
        if(!$route){
            $this->fire(404, ['route' => &$route]);
            return false;
        }

        // env data
        foreach($route->env as $key => $value){
            env($key, $value);
        }

        return $this->_resolve($route);
    }


    /**
     * Step 2 : action resolving
     * @param Route $route
     * @return bool|mixed
     */
    protected function _resolve(Route $route)
    {
        // resolve
        $build = $this->_builder->resolve($route->target);
        $this->fire('resolve', ['build' => &$build]);

        // 403
        if(isset($build->metadata['auth']) and (int)$build->metadata['auth'] < Auth::rank()){
            $this->fire(403, ['build' => &$build]);
            return false;
        }

        return $this->_call($build, $route->args);
    }


    /**
     * Step 3 : action calling
     * @param Build $build
     * @param array     $args
     * @return mixed
     */
    protected function _call(Build $build, array $args = [])
    {
        // call
        $data = $this->_builder->call($build->action, $args);
        $this->fire('call', ['build' => &$build, 'data' => &$data]);

        // need rendering ?
        if(!empty($build->metadata['view'])){
            $this->_render((array)$data, $build->metadata);
        }

        return $data;
    }


    /**
     * Step 4 : view rendering (optional)
     * @param       $data
     * @param array $metadata
     */
    protected function _render(array $data, array $metadata = [])
    {
        // create view
        $view = new View($metadata['view'], $data);
        $this->fire('render', ['view' => &$view, 'data' => &$data, 'metadata' => &$metadata]);
        echo $view->display();
    }

}