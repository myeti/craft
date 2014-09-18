<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Kit;

class Action
{

    const CLOSURE = 1;
    const INVOKE = 2;
    const METHOD = 3;
    const STATIC_METHOD = 4;

    /** @var callable */
    public $callable;

    /** @var array */
    public $args = [];

    /** @var array */
    public $meta = [];

    /** @var \ReflectionFunctionAbstract */
    public $ref;

    /** @var int */
    public $type = self::CLOSURE;


    /**
     * Create action
     * @param callable $callable
     * @param array $args
     * @param array $meta
     * @param int $type
     * @param \Reflector $ref
     */
    public function __construct(callable $callable, array $args = [], array $meta = [], $type = self::CLOSURE, \ReflectionFunctionAbstract $ref = null)
    {
        $this->callable = $callable;
        $this->args = $args;
        $this->meta = $meta;
        $this->type = $type;
        $this->ref = $ref;
    }


    /**
     * Execute action
     * @return mixed
     */
    public function __invoke(...$args)
    {
        $args = $args ?: $this->args; // args take over
        return $this->data = call_user_func_array($this->callable, $args);
    }


    /**
     * Resolve action
     * @param mixed $action
     * @param InjectorInterface $injector
     * @return Action
     */
    public static function resolve($action, InjectorInterface $injector = null)
    {
        // resolve function/closure
        if($action instanceof \Closure or function_exists($action)) {

            // read function & meta
            $function = new \ReflectionFunction($action);
            $meta = Metadata::parse($function);

            // create action
            return new Action($action, [], $meta, Action::CLOSURE, $function);
        }

        // parse class::method to [class, method]
        if(is_string($action) and strpos($action, '::') !== false) {
            $action = explode('::', $action);
        }

        // resolve [class, method]
        if(is_array($action) and count($action) === 2) {

            // read method
            $method = new \ReflectionMethod($action[0], $action[1]);

            // static method
            if($method->isPublic() and $method->isStatic()) {

                // read class & meta
                $class = new \ReflectionClass($action[0]);
                $meta = array_merge(Metadata::parse($class), Metadata::parse($method));

                // create action
                return new Action($action, [], $meta, Action::STATIC_METHOD, $method);
            }
            // normal method
            elseif($method->isPublic() and !$method->isStatic() and !$method->isAbstract()) {

                // read class & meta
                $class = new \ReflectionClass($action[0]);
                $meta = array_merge(Metadata::parse($class), Metadata::parse($method));

                // create object
                if(!is_object($action[0])) {
                    $action[0] = $injector ? $injector->make($action[0]) : $class->newInstance();
                }

                // create action
                return new Action($action, [], $meta, Action::METHOD, $method);
            }

        }

        // resolve __invoke
        if((is_object($action) or class_exists($action)) and method_exists($action, '__invoke')) {

            // read class, method & meta
            $class = new \ReflectionClass($action);
            $method = new \ReflectionMethod($action, '__invoke');
            $meta = array_merge(Metadata::parse($class), Metadata::parse($method));

            // create object
            if(!is_object($action)) {
                $action = $injector ? $injector->make($action) : $class->newInstance();
            }

            // create action
            return new Action($action, [], $meta, Action::INVOKE, $method);
        }

        return false;
    }

}