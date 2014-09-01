<?php

namespace Craft\Trace;

use Forge\Engine;

abstract class Panel
{

    /** @var Panel\Stack[] */
    protected static $stacks = [];


    /**
     * Open stack
     * @param string $stack
     * @return Panel\Stack
     */
    public static function in($stack)
    {
        if(!isset(static::$stacks[$stack])) {
            static::$stacks[$stack] = new Panel\Stack;
        }

        return static::$stacks[$stack];
    }


    /**
     * Render console
     */
    public static function render()
    {
        return Engine::make(__DIR__ . '/Panel/template', ['stacks' => static::$stacks]);
    }

} 