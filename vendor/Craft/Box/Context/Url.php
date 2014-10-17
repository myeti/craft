<?php

namespace Craft\Box\Context;

class Url
{

    /** @var string */
    public $full;

    /** @var string */
    public $host;

    /** @var string */
    public $base;

    /** @var string */
    public $query;

    /** @var string */
    public $from;


    /**
     * Return full url
     * @return mixed
     */
    public function __toString()
    {
        return (string)$this->full;
    }

} 