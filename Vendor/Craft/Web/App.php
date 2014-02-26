<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Web;

use Craft\Box\Mog;
use Craft\Error\Abort;
use Craft\Reflect\Event;
use Craft\Reflect\Injector;
use Craft\Reflect\Resolver;
use Craft\Router\Basic as Router;
use Craft\View\Engine;
use Craft\View\Engine\NativeEngine;
use Craft\View\Engine\Native\Helper\Asset;

class App extends Kernel\Wrapper
{

    use Event;


    /**
     * Main process
     * @param Request $request
     * @throws \Exception
     * @return mixed
     */
    public function handle(Request $request)
    {
        // before event
        $this->fire('app.start', [&$request]);

        // run handler
        try {
            list($request, $response) = parent::handle($request);
        }
        // catch abort as event
        catch(Abort $e) {
            if(!$this->fire($e->getCode(), [$request, $e->getMessage()])) { throw $e; }
            return false;
        }

        // after event
        $this->fire('app.end', [&$request, &$response]);

        return [$request, $response];
    }


    /**
     * Resolve and perform query
     * @param string $query
     * @return mixed
     */
    public function plug($query = null)
    {
        // resolve query
        if(!$query) {
            $query = Mog::url();
            $query = substr($query, strlen(Mog::base()));
            $query = parse_url($query, PHP_URL_PATH);
        }

        // create request
        $request = new Request();
        $request->query = $query;

        // perform
        return $this->handle($request);
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


    /**
     * Forge app from routes
     * @param array $routes
     * @param Injector $injector
     * @param Kernel\Firewall\Strategy $strategy
     * @return App
     */
    public static function forge(array $routes, Injector $injector = null, Kernel\Firewall\Strategy $strategy = null)
    {
        // kernel
        $kernel = new Kernel();

        // firewall
        $strategy = $strategy ?: new Kernel\Firewall\RankStrategy();
        $firewall = new Kernel\Firewall($kernel, $strategy);

        // reflector
        $resolver = new Resolver($injector);
        $reflector = new Kernel\Reflector($firewall, $resolver);

        // dispatcher
        $router = new Router($routes);
        $dispatcher = new Kernel\Dispatcher($reflector, $router);

        // formatter
        $engine = new NativeEngine(Mog::path(), 'php');
        $engine->mount(new Asset(Mog::base()));
        $formatter = new Kernel\Formatter($dispatcher, $engine);

        // create app
        return new self($formatter);
    }

}