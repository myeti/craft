<?php

namespace Craft\Box\Context;

class Http
{

    /** @var int */
    public $code = 200;

    /** @var bool */
    public $secure = false;

    /** @var bool */
    public $ajax = false;

    /** @var string */
    public $method = 'GET';

} 