<?php

namespace Craft\Reflect\Resolver;

use Craft\Reflect\Action;
use Craft\Reflect\Annotation;

class ClassMethodResolver implements TypeResolver
{

    /**
     * Is type
     * @param mixed $input
     * @return bool
     */
    public function is($input)
    {
        // parse string
        if(is_string($input) and strpos($input, '::') !== false) {
            $input = explode('::', $input);
        }

        // reflect tuple
        if(is_callable($input) and is_array($input) and count($input) == 2) {
            $method = new \ReflectionMethod($input[0], $input[1]);
            if($method->isPublic() and !$method->isStatic() and !$method->isAbstract()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Resolve closure with metadata
     * @param mixed $input
     * @return Action
     */
    public function resolve($input)
    {
        // parse string
        $parsed = is_string($input) ? explode('::', $input) : $input;

        // reflect class
        $class = new \ReflectionClass($parsed[0]);
        $object = $class->newInstance();

        // reflect method
        $method = new \ReflectionMethod($object, $parsed[1]);

        // get metadata
        $metadata = array_merge(
            Annotation::get($class),
            Annotation::get($method)
        );

        // set callable
        $callable = [$object, $parsed[1]];

        return new Action($callable, $metadata);
    }

}