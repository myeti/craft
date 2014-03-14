<?php

namespace Craft\App\Handler;

use Craft\App\Request;
use Craft\App\Response;

abstract class After extends Before
{

    /**
     * Handle response
     * @param Response $response
     * @param Request $request
     * @return Response
     */
    abstract public function handle(Request $request, Response $response = null);

} 