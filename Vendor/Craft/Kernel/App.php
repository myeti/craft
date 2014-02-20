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
use Craft\Reflect\Injector;
use Craft\Router\Matcher;
use Craft\Router\Web as WebRouter;
use Craft\View\Engine;
use Craft\View\Engine\NativeEngine;
use Craft\View\Engine\Native\Helper\Asset;

class App extends Dispatcher
{

    /** @var Matcher */
    protected $router;

    /** @var Engine */
    protected $engine;


    /**
     * Setup router
     * @param array $routes
     * @param Injector $injector
     * @param Engine $engine
     */
    public function __construct(array $routes, Injector $injector = null, Engine $engine = null)
    {
        // init router
        $this->router = new WebRouter($routes);

        // init engine
        if(!$engine) {
            $engine = new NativeEngine(dirname(Mog::server('SCRIPT_FILENAME')), 'php', []);
            $engine->mount(new Asset(Mog::base()));
        }
        $this->engine = $engine;

        // init dispatcher
        parent::__construct($injector);
    }


    /**
     * Resolve and perform query
     * @param string $query
     * @return mixed
     */
    public function plug($query = null)
    {
        // resolve query
        $query = $query ?: $this->query();

        // create request
        $request = new Request($query);

        // create context
        $context = new Context($request);

        // perform
        return $this->handle($context);
    }


    /**
     * Main process
     * @param Context $context
     * @return mixed
     */
    public function handle(Context $context)
    {
        // start
        $this->fire('app.start', [&$context]);

        // run router
        $this->fire('app.route', [&$context]);
        $context->route = $this->route($context);

        // 404
        if(!$context->route) {
            $this->fire(404, ['message' => 'Route for query "' . $context->request->query . '" not found.']);
            return false;
        }

        // set env data
        foreach($context->route->data['envs'] as $key => $value) {
            Config::set($key, $value);
        }

        // run dispatcher
        $context = $this->dispatch($context, $this->engine);

        // stop
        $this->fire('app.stop', [&$context]);
        return $context;
    }


    /**
     * Get request query
     * @return string
     */
    protected function query()
    {
        $query = Mog::url();
        $query = substr($query, strlen(Mog::base()));
        return parse_url($query, PHP_URL_PATH);
    }


    /**
     * Find route with query
     * @param Context $context
     * @return Route
     */
    protected function route(Context $context)
    {
        return $this->router->find($context->request->query);
    }


    /**
     * Run dispatcher
     * @param Context $context
     * @param Engine $engine
     * @return Context
     */
    protected function dispatch(Context $context, Engine $engine = null)
    {
        // resolve args
        $context->route->data = isset($context->route->data['args'])
            ? $context->route->data['args']
            : [];

        return parent::handle($context, $engine);
    }


    /**
     * App as a service
     * @param string $query
     * @return mixed
     */
    public function __invoke($query = null)
    {
        return $this->plug($query);
    }

}