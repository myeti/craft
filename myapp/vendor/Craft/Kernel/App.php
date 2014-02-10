<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Kernel;

use Craft\Env\Config;
use Craft\Env\Mog;
use Craft\Pattern\Event\Subject;
use Craft\Router\Matcher;
use Craft\Router\Matcher\UrlMatcher;
use Craft\Router\Route;
use Craft\Router\RouteProvider;
use Craft\View\Engine;
use Craft\View\Helper\Asset;
use Craft\View\Helper\Html;
use Craft\View\Template;
use Craft\View\Viewable;

class App extends Dispatcher
{

    /** @var Matcher */
    protected $router;

    /** @var array */
    protected $config = [
        'template.dir'  => '/',
        'template.ext'  => 'php',
        'template.helpers' => []
    ];


    /**
     * Setup router
     * @param array $routes
     * @param array $config
     */
    public function __construct(array $routes, array $config = [])
    {
        // init router
        $this->router = new UrlMatcher(new RouteProvider($routes));

        // set config
        $this->config = $config + [
            'injector'         => null,
            'template.ext'     => 'php',
            'template.dir'     => dirname($_SERVER['SCRIPT_FILENAME']),
            'template.helpers' => [
                new Asset(Mog::base()),
                new Html()
            ]
        ];

        parent::__construct($this->config['injector']);
    }

    /**
     * Main process
     * @param string $query
     * @param bool $service
     * @return mixed
     */
    public function plug($query = null, $service = false)
    {
        // start
        $this->fire('app.start', [&$query, &$service]);

        // resolve protocol query
        $query = $this->resolveQuery($query);

        // run router
        $this->fire('app.route', [&$query]);
        $route = $this->findRoute($query);

        // 404
        if(!$route) {
            $this->fire(404, ['message' => 'Route "' . $query . '" not found.']);
            return false;
        }

        // set env data
        foreach($route->data['envs'] as $key => $value) {
            Config::set($key, $value);
        }

        // init view
        $view = !$service ? $this->createView() : null;

        // run dispatcher
        $data = $this->dispatch($route, $view);

        // stop
        $this->fire('app.stop', []);
        return $data;
    }


    /**
     * Resolve default input query
     * @param $query
     * @return mixed|string
     */
    protected function resolveQuery($query)
    {
        if(!$query) {
            $query = Mog::url();
            $query = substr($query, strlen(Mog::base()));
            $query = parse_url($query, PHP_URL_PATH);
        }

        return $query;
    }


    /**
     * Find route with query
     * @param $query
     * @return \Craft\Router\Route
     */
    protected function findRoute($query)
    {
        $route = $this->router->find($query);

        return $route;
    }


    /**
     * Prepare view with config
     * @return \Craft\View\Template
     */
    protected function createView()
    {
        $engine = new Engine($this->config['template.dir'], $this->config['template.ext']);
        foreach($this->config['template.helpers'] as $helper) {
            $helper = is_string($helper) ? new $helper() : $helper;
            $engine->mount($helper);
        }

        return new Template($engine);
    }


    /**
     * Run dispatcher
     * @param Route $route
     * @param Viewable $view
     * @return mixed
     */
    protected function dispatch(Route $route, Viewable $view = null)
    {
        $args = isset($route->data['args']) ? $route->data['args'] : [];
        return $this->perform($route->target, $args, $view);
    }


    /**
     * App as a service
     * @param string $query
     * @param bool $service
     * @return mixed
     */
    public function __invoke($query = null, $service = false)
    {
        return $this->plug($query, $service);
    }

}