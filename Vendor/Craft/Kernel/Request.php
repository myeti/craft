<?php

namespace Craft\Kernel;

class Request
{

    /** @var string */
    public $query;

    /** @var array */
    public $args = [];

    /** @var string */
    public $method = 'GET';

    /**
     * @param $query
     * @param string $method
     */
    public function __construct($query, $method = 'GET')
    {
        $this->query = $query;
        $this->method = $method;
    }

}