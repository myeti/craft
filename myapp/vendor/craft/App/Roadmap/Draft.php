<?php

namespace Craft\App\Roadmap;

class Draft
{

    /** @var \Closure */
    public $callable;

    /** @var array */
    public $metadata = [];

    /** @var string */
    public $type;

    /** @var array */
    public $args = [];

    /** @var mixed */
    public $data;

} 