<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Web\Kernel;

use Craft\Router\MatcherInterface;
use Craft\Web\Event\NotFound;
use Craft\Web\Handler;
use Craft\Web\Request;
use Craft\Web\Response;

class Dispatcher implements Handler
{

    /** @var Handler */
    protected $handler;

    /** @var MatcherInterface */
    protected $router;


    /**
     * Setup kernel
     * @param Handler $handler
     * @param MatcherInterface $router
     */
    public function __construct(Handler $handler, MatcherInterface $router)
    {
        $this->handler = $handler;
        $this->router = $router;
    }


    /**
     * Handle context request
     * @param Request $request
     * @throws NotFound
     * @return Response
     */
    public function handle(Request $request)
    {
        // route query
        $route = $this->router->find($request->query);

        // 404
        if(!$route) {
            throw new NotFound('Route "' . $request->query . '" not found.');
        }

        // update request
        $request->action = $route->to;
        $request->args = $route->data;

        // run dispatcher
        return $this->handler->handle($request);
    }

} 