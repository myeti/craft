<?php

namespace Craft\App\Response;

use Craft\App\Response;

class Json extends Response
{

    public $format = 'application/json';

    /**
     * New json response
     * @param array $content
     * @param int $code
     * @param array $headers
     */
    public function __construct(array $content = [], $code = 200, array $headers = [])
    {
        $this->content = json_encode($content, JSON_PRETTY_PRINT);
        $this->code = $code;
        $this->headers = $headers;
    }

} 