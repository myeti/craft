<?php

namespace Craft\App\Response;

use Craft\App\Response;

class Redirect extends Response
{

    /**
     * New redirect response
     * @param string $url
     * @param int $code
     * @param array $headers
     */
    public function __construct($url, $code = 200, array $headers = [])
    {
        $this->code = $code;
        $this->headers = $headers;
        $this->header('Location', $url);
    }


    /**
     * Stop execution
     * when sending response
     */
    public function send()
    {
        parent::send();
        exit;
    }

} 