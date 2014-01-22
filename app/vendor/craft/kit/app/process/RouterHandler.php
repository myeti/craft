<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\app\process;

use craft\kit\app\Context;
use craft\kit\app\ContextHandler;
use craft\kit\dispatcher\DispatchException;
use craft\kit\router\Router;
use craft\box\env\Env;

/**
 * Class Router
 * Find route with query
 */
class RouterHandler extends ContextHandler
{

    /** @var Router */
    protected $_router;


    /**
     * Create router
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        $this->_router = new Router($routes);
    }


    /**
     * Get handler name
     * @return string
     */
    public function name()
    {
        return 'router';
    }


    /**
     * Handle routing
     * @param Context $context
     * @throws DispatchException
     * @return Context
     */
    public function handleContext(Context $context)
    {
        // get query
        $context->query = '/' . ltrim($context->query, '/');

        // route
        $route = $this->_router->find($context->query);

        // 404
        if(!$route){
            $context->error = 404;
            throw new DispatchException(404, 'Route "' . $context->query . '" not found.');
        }

        // env env
        foreach($route->env as $key => $value){
            Env::set($key, $value);
        }

        // set route
        $route->query = $context->query;
        $context->route = $route;

        return $context;
    }

}