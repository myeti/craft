<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\core\handlers;

use craft\core\Handler;
use craft\core\Context;
use craft\core\data\Build;
use craft\box\Annotation;
use craft\data\Auth;
use craft\meta\EventException;

/**
 * Class Builder
 * Build and call action with the Route
 */
class Builder implements Handler
{

    /**
     * Handle context
     * @param Context $context
     * @throws \RuntimeException
     * @return mixed|void
     */
    public function handle(Context $context)
    {
        // resolve
        $build = $this->resolve($context->route->target);
        $context->build = $build;

        // firewall
        if(isset($build->metadata['auth']) and Auth::rank() < (int)$build->metadata['auth']) {
            throw new EventException(403);
        }

        return $context;
    }


    /**
     * Turn target into \Closure and get metadata
     * @param $target
     * @throws \InvalidArgumentException
     * @return bool|Build
     */
    public function resolve($target)
    {
        // init
        $parentRef = null;

        // function
        if($target instanceof \Closure or (is_string($target) and function_exists($target))) {
            // apply reflection
            $ref = new \ReflectionFunction($target);
            $closure = $target instanceof \Closure ? $target : $ref->getClosure();
        }
        // method in array
        elseif(($is_string = is_string($target) and strpos($target, '::')) or (is_array($target) and count($target) === 2)) {

            // resolve class::method
            if($is_string){
                $target = explode('::', $target);
            }

            // apply reflection
            $ref = new \ReflectionMethod($target[0], $target[1]);
            $closure = $ref->isStatic() ? $ref->getClosure() : $ref->getClosure(new $target[0]());

            // parent reflection
            $parentRef = new \ReflectionClass($target[0]);
        }
        else {
            throw new \InvalidArgumentException;
        }

        // get metadata
        $metadata = Annotation::get($ref) ?: [];

        // merge with parent metadata
        if($parentRef and $parentRef instanceof \ReflectionClass){
            $parentMetadata = Annotation::get($parentRef) ?: [];
            $metadata = array_merge($parentMetadata, $metadata);
        }

        // make statement
        $build = new Build();
        $build->action = $closure;
        $build->metadata = $metadata;

        return $build;
    }

}