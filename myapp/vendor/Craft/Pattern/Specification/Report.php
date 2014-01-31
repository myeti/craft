<?php

namespace Craft\Pattern\Specification;

class Report
{

    /** @var Item */
    public $item;

    /** @var bool */
    public $pass = true;

    /** @var string[] */
    public $errors = [];

} 