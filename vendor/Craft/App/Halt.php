<?php

namespace Craft\App;

class Halt extends \Exception
{

    /** @var callable */
    public $callback;


    /**
     * Attach callable
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
        parent::__construct();
    }

} 