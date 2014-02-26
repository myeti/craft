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

use Craft\Web\Event\Forbidden;
use Craft\Web\Handler;
use Craft\Web\Request;
use Craft\Web\Response;

class Firewall implements Handler
{

    /** @var Handler */
    protected $handler;

    /** @var Firewall\Strategy */
    protected $strategy;


    /**
     * Setup firewall
     * @param Handler $handler
     * @param Firewall\Strategy $strategy
     */
    public function __construct(Handler $handler, Firewall\Strategy $strategy)
    {
        $this->handler = $handler;
        $this->strategy = $strategy;
    }


    /**
     * Handle context request
     * @param Request $request
     * @throws Forbidden
     * @return Response
     */
    public function handle(Request $request)
    {
        // 403
        if(!$this->strategy->pass($request)) {
            throw new Forbidden('User not allowed for query "' . $request->query . '"');
        }

        // run inner handler
        return $this->handler->handle($request);
    }

}