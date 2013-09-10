<?php

namespace craft;

use craft\session\Auth;

class App
{

    use Events;

    /** @var Router */
    protected $_router;

    /** @var Builder */
    protected $_builder;

    /** @var View */
    protected $_view;

    /**
     * Setup with components
     * @param Router $router
     * @param Builder $builder
     * @param View $view
     */
    public function __construct(Router $router, Builder $builder = null, View $view = null)
    {
        $this->_router = $router;
        $this->_builder = $builder ?: new Builder();
        $this->_view = $view ?: new View();
    }

    /**
     * Main process
     */
    public function process($query = null)
    {
        // resolve query
        $query = $query ?: $_SERVER['QUERY_STRING'];

        // start process
        $this->fire('start', ['query' => $query]);

        // route
        $route = $this->_router->find($query);
        $this->fire('route', ['route' => $route]);

        // 404
        if(!$route){
            $this->fire(404, ['route' => $route]);
        }
        else {

            // resolve
            $stm = $this->_builder->resolve($route->target);
            $this->fire('resolve', ['statement', $stm]);

            // 403
            if(isset($stm->metadata['auth']) and $stm->metadata['auth'] < Auth::rank()){
                $this->fire(403, ['statement', $stm]);
            }
            else {

                // build
                $data = $this->_builder->call($stm->action, $route->args);
                $this->fire('call', ['statement', $stm, 'data' => $data]);

                // render
                $this->_view->render($data, $stm->metadata);
                $this->fire('render', ['statement', $stm, 'data' => $data]);
            }

        }

        // end process
        $this->fire('end');
    }

}