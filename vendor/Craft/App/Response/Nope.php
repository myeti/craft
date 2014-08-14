<?php

namespace Craft\App\Response;

use Craft\App\Response;

class Nope extends Response
{

    /**
     * New nope response
     * @param int $code
     * @param array $headers
     */
    public function __construct($code = 200, array $headers = [])
    {
        $this->content = '<h1>NOPE.</h1>';
        $this->code = $code;
        $this->headers = $headers;
    }

} 