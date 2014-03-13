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

use Craft\Web\Handler;
use Craft\Web\Request;
use Craft\Web\Response;

class Wrapper implements Handler
{

    /** @var Handler */
    protected $handler;

    /** @var Handler[] */
    protected $before = [];

    /** @var Handler[] */
    protected $after = [];


    /**
     * Set main handler
     * @param Handler $handler
     */
    public function __construct(handler $handler)
    {
        $this->handler = $handler;
    }


    /**
     * Add before handler
     * @param Handler $handler
     * @return $this;
     */
    public function before(handler $handler)
    {
        $this->before[] = $handler;
        return $this;
    }


    /**
     * Add after handler
     * @param Handler $handler
     * @return $this
     */
    public function after(handler $handler)
    {
        $this->after[] = $handler;
        return $this;
    }


    /**
     * Handle context request
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request)
    {
        // run before, only impact request
        foreach($this->before as $before) {
            list($request) = $before->handle($request);
        }

        // run main
        $response = $this->handler->handle($request);

        // run after, impact both request and response
        foreach($this->after as $after) {
            $response = $after->handle($response->request, $response);
        }

        return $response;
    }

}