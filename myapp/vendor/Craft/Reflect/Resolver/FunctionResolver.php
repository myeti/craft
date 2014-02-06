<?php

namespace Craft\Reflect\Resolver;

use Craft\Reflect\Action;
use Craft\Reflect\Annotation;

class FunctionResolver implements TypeResolver
{

    /**
     * Is type
     * @param mixed $input
     * @return bool
     */
    public function is($input)
    {
        $isFunction = is_string($input) && function_exists($input);
        $isClosure = $input instanceof \Closure;

        return $isClosure || $isFunction;
    }

    /**
     * Resolve closure with metadata
     * @param mixed $input
     * @return Action
     */
    public function resolve($input)
    {
        $function = new \ReflectionFunction($input);
        $metadata = Annotation::get($function);

        return new Action($input, $metadata);
    }

}