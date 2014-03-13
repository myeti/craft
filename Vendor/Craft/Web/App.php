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
use Craft\View\Native\Engine;
use Craft\View\Native\Helper\Asset;

class App extends Kernel\Wrapper
{

    use Event;


    /**
     * Create web app from routes
     * @param array $routes
     * @param Injector $injector
     * @param Kernel\Firewall\Strategy $strategy
     */
    public function __construct(array $routes, Injector $injector = null, Kernel\Firewall\Strategy $strategy = null)
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
        $engine = new Engine(Mog::path(), 'php');
        $engine->mount(new Asset(Mog::base()));
        $formatter = new Kernel\Formatter($dispatcher, $engine);

        // create app
        parent::__construct($formatter);
    }


    /**
     * Main process
     * @param Request $request
     * @throws \Exception
     * @return mixed
     */
    public function handle(Request $request = null)
    {
        // before event
        $this->fire('app.start', [&$request]);

        // resolve request
        if(!$request) {
            $query = Mog::url();
            $query = substr($query, strlen(Mog::base()));
            $query = parse_url($query, PHP_URL_PATH);
            $request = new Request();
            $request->query = $query;
        }

        // run handler
        try {
            $response = parent::handle($request);
        }
        // catch abort as event
        catch(Abort $e) {
            if(!$this->fire($e->getCode(), [$request, $e->getMessage()])) { throw $e; }
            return false;
        }

        // after event
        $this->fire('app.end', [&$request, &$response]);

        return $response;
    }


    /**
     * Resolve and perform query
     * @param string $query
     * @return mixed
     */
    public function to($query)
    {
        // create request
        $request = new Request();
        $request->query = $query;

        // perform
        return $this->handle($request);
    }

}