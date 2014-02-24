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
use Craft\Router\Web as WebRouter;
use Craft\View\Engine;
use Craft\View\Engine\NativeEngine;
use Craft\View\Engine\Native\Helper\Asset;

class App implements Handler
{

    use Event;

    /** @var Handler */
    protected $handler;

    /** @var Engine */
    protected $engine;


    /**
     * Setup App
     * @param Handler $handler
     * @param Engine $engine
     */
    public function __construct(Handler $handler, Engine $engine = null)
    {
        $this->handler = $handler;
        $this->engine = $engine;
    }


    /**
     * Middleware : before
     * @param Handler $handler
     */
    public function before(Handler $handler)
    {
        $this->on('before', function(Request $request) use($handler) {
            return $handler->handle($request);
        });
    }


    /**
     * Middleware : before
     * @param Handler $handler
     */
    public function after(Handler $handler)
    {
        $this->on('after', function(Request $request, Response $response) use($handler) {
            return $handler->handle($request, $response);
        });
    }


    /**
     * Main process
     * @param Request $request
     * @throws \Exception
     * @return mixed
     */
    public function handle(Request $request)
    {
        // before event
        $this->fire('before', [&$request]);

        // run handler
        try {
            list($request, $response) = $this->handler->handle($request);
        }
        // catch abort as event
        catch(Abort $e) {
            if(!$this->fire($e->getCode(), [$request, $e->getMessage()])) { throw $e; }
            return false;
        }

        // after event
        $this->fire('after', [&$request, &$response]);

        // render if asked
        if(!empty($request->meta['render'])) {

            // run engine
            $content = $this->engine->render($request->meta['render'], $response->data);

            // update response
            $response->content = $content;

            // send response
            echo $response;

        }

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
     * @param Firewall\Strategy $strategy
     * @return App
     */
    public static function forge(array $routes, Injector $injector = null, Firewall\Strategy $strategy = null)
    {
        // create resolver
        $resolver = new Resolver($injector);

        // create dispatcher
        $dispatcher = new Dispatcher($resolver);

        // create firewall
        $firewall = new Firewall($dispatcher, $strategy ?: new Firewall\RankStrategy());

        // create router
        $router = new WebRouter($routes);

        // create kernel
        $kernel = new Kernel($firewall, $router);

        // create engine
        $engine = new NativeEngine(Mog::path(), 'php');
        $engine->mount(
            new Asset(Mog::base())
        );

        // create app
        return new self($kernel, $engine);
    }

}