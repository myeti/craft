<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App;

/**
 * A service operates before
 * and/or after the action is called
 */
abstract class Service
{

    /** @var string */
    public $name;

    /**
     * Handle request
     * @param Request $request
     * @return Request
     */
    public function before(Request $request)
    {
        return $request;
    }

    /**
     * Handle response
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function after(Request $request, Response $response)
    {
        return $response;
    }

    /**
     * End of execution
     * @param Request $request
     * @param Response $response
     */
    public function finish(Request $request, Response $response) {}

    /**
     * Handle error
     * @param \Exception $e
     * @param Request $request
     * @param Response $response
     * @return null|Response
     */
    public function error(\Exception $e, Request $request, Response $response = null)
    {
        return null;
    }

} 