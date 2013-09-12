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
    public function process($query = null)
    {
        // resolve query
        $query = $query ?: $_SERVER['QUERY_STRING'];

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
            return false;
        }

        return $this->_resolve($route);
    }


    /**
     * Step 2 : action resolving
     * @param \stdClass $route
     * @return bool|mixed
     */
    protected function _resolve(\stdClass $route)
    {
        // resolve
        $build = $this->_builder->resolve($route->target);
        $this->fire('resolve', ['build' => &$build]);

        // 403
        if(isset($build->metadata['auth']) and $build->metadata['auth'] < Auth::rank()){
            $this->fire(403, ['build' => &$build]);
            return false;
        }

        return $this->_call($build, $route->args);
    }


    /**
     * Step 3 : action calling
     * @param \stdClass $build
     * @param array     $args
     * @return mixed
     */
    protected function _call(\stdClass $build, array $args = [])
    {
        // call
        $data = $this->_builder->call($build->action, $args);
        $this->fire('call', ['build' => &$build, 'data' => &$data]);

        // need rendering ?
        if(!empty($build->metadata['view'])){
            $this->_render($data, $build->metadata);
        }

        return $data;
    }


    /**
     * Step 4 : view rendering (optional)
     * @param       $data
     * @param array $metadata
     */
    protected function _render($data, array $metadata = [])
    {
        // create view
        $view = new View($metadata['view'], $data);
        $this->fire('render', ['view' => &$view, 'data' => &$data, 'metadata' => &$metadata]);
        echo $view;
    }


    /**
     * Thank you :)
     */
    public function __toString()
    {
        return 'Handcrafted with love :)';
    }

}