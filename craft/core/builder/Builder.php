<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\core\builder;

use craft\box\Annotation;

class Builder
{

    /** @var array */
    protected $_config = [
        'base' => null
    ];


    /**
     * Setup with config
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->_config = $config + $this->_config;
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

            // base namespace
            $target[0] = $this->_config['base'] . $target[0];

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
            $metadata = $parentMetadata + $metadata;
        }

        // make statement
        $build = new Build();
        $build->action = $closure;
        $build->metadata = $metadata;

        return $build;
    }


    /**
     * Call action
     * @param Closure $action
     * @param array $args
     * @return mixed
     */
    public function call(\Closure $action, array $args = [])
    {
        return call_user_func_array($action, $args);
    }

}