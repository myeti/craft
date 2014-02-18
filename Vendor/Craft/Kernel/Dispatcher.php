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

use Craft\Env\Auth;
use Craft\Router\Route;
use Craft\View\Engine;
use Craft\Reflect\Event;
use Craft\Reflect\Action;
use Craft\Reflect\Injector;
use Craft\Reflect\Resolver;

class Dispatcher implements Handler
{

    use Event;

    /** @var Injector */
    protected $injector;


    /**
     * Setup dispatcher with dependency injector
     * @param Injector $injector
     */
    public function __construct(Injector $injector = null)
    {
        $this->injector = $injector ?: new Injector();
    }


    /**
     * Resolve query and handle
     * @param $query
     * @param array $args
     * @param Engine $engine
     * @return mixed
     */
    public function perform($query, array $args = [], Engine $engine = null)
    {
        // create request
        $request = new Request($query);
        $request->args = $args;

        // create route based on request
        $route = new Route($query, $query);
        $route->data = $args;

        // create context
        $context = new Context($request, $route);

        return $this->handle($context, $engine);
    }


    /**
     * Run action & render template
     * @param Context $context
     * @param Engine $engine
     * @return mixed
     */
    public function handle(Context $context, Engine $engine = null)
    {
        // start
        $this->fire('dispatcher.start', [&$context]);

        // resolve
        $this->fire('dispatcher.resolve', [&$context]);
        $context->action = $this->resolve($context);

        // firewall
        $this->fire('dispatcher.firewall', [&$context]);
        if(!$this->firewall($context)) {
            $this->fire(403, [&$context]);
            return false;
        }

        // call
        $this->fire('dispatcher.call', [&$context]);
        $this->call($context);

        // render
        $this->fire('dispatcher.render', [&$context, &$engine]);
        if($engine) {
            $this->render($context, $engine);
        }

        // stop
        $this->fire('dispatcher.stop', [&$context]);
        return $context;
    }


    /**
     * Resolve and prepare action
     * @param Context $context
     * @throws \BadMethodCallException
     * @return Action
     */
    protected function resolve(Context $context)
    {
        $action = Resolver::resolve($context->route->to, $this->injector);
        if(!$action) {
            throw new \BadMethodCallException('This action is not a valid callable.');
        }

        $action->args = $context->route->data;
        $action->metadata += [
            'render' => null,
            'auth'   => 0
        ];

        return $action;
    }


    /**
     * Gate keeper : check auth
     * @param Context $context
     * @return bool
     */
    protected function firewall(Context $context)
    {
        return Auth::allowed($context->action->metadata['auth']);
    }


    /**
     * Execute action
     * @param Context $context
     * @return mixed
     */
    protected function call(Context $context)
    {
        // resolve args
        $args = $context->action->args;
        $args[] = &$context;

        return call_user_func_array($context->action, $args);
    }


    /**
     * Render view
     * @param Engine $engine
     * @param Context $context
     */
    protected function render(Context $context, Engine $engine)
    {
        // resolve data
        $data = isset($context->action->data) ? $context->action->data : [];

        echo $engine->render($context->action->metadata['render'], $data);
    }

}

