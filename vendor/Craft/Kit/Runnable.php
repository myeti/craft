<?php

namespace Craft\Kit;

class Runnable
{

    const UNKNOWN = 0;
    const CLOSURE = 1;
    const INVOKABLE = 2;
    const METHOD = 3;
    const STATIC_METHOD = 4;

    /** @var callable */
    public $source;

    /** @var callable */
    public $callable;

    /** @var array */
    public $args = [];

    /** @var array */
    public $meta = [];

    /** @var \ReflectionFunctionAbstract */
    public $reflector;

    /** @var int */
    public $type = self::UNKNOWN;


    /**
     * Parse source to create runnable
     * @param callable|mixed $source
     * @param array $args
     */
    public function __construct($source, ...$args)
    {
        $this->source = $source;
        $this->args = $args;

        if(!$this->resolve()) {
            throw new \LogicException('Could not resolve runnable');
        }
    }


    /**
     * Get meta value
     * @param string $key
     * @return string
     */
    public function meta($key)
    {
        return isset($this->meta[$key]) ? $this->meta[$key] : null;
    }


    /**
     * Run callable
     * @param array $args
     * @return mixed
     */
    public function call(...$args)
    {
        $args = array_merge($this->args, $args);
        return call_user_func_array($this->callable, $args);
    }


    /**
     * Alias of call()
     * @param array $args
     * @return mixed
     */
    public function __invoke(...$args)
    {
        return $this->call(...$args);
    }


    /**
     * Resolve runnable
     * @return bool
     */
    protected function resolve()
    {
        // init
        $action = $this->source;

        // resolve function/closure
        if($action instanceof \Closure or (is_string($action) and function_exists($action))) {

            // read function & meta
            $function = new \ReflectionFunction($action);
            $meta = Annot::parse($function);

            // create action
            $this->callable = $action;
            $this->reflector = $function;
            $this->meta = $meta;
            $this->type = self::CLOSURE;

            return true;
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
                $meta = array_merge(Annot::parse($class), Annot::parse($method));

                // create action
                $this->callable = $action;
                $this->reflector = $method;
                $this->meta = $meta;
                $this->type = self::STATIC_METHOD;

                return true;
            }
            // normal method
            elseif($method->isPublic() and !$method->isStatic() and !$method->isAbstract()) {

                // read class & meta
                $class = new \ReflectionClass($action[0]);
                $meta = array_merge(Annot::parse($class), Annot::parse($method));

                // create object
                if(!is_object($action[0])) {
                    $action[0] = $class->newInstance();
                }

                // create action
                $this->callable = $action;
                $this->reflector = $method;
                $this->meta = $meta;
                $this->type = self::METHOD;

                return true;
            }

        }

        // resolve __invoke
        if((is_object($action) or class_exists($action)) and method_exists($action, '__invoke')) {

            // read class, method & meta
            $class = new \ReflectionClass($action);
            $method = new \ReflectionMethod($action, '__invoke');
            $meta = array_merge(Annot::parse($class), Annot::parse($method));

            // create object
            if(!is_object($action)) {
                $action = $class->newInstance();
            }

            // create action
            $this->callable = $action;
            $this->reflector = $method;
            $this->meta = $meta;
            $this->type = self::INVOKABLE;

            return true;
        }

        return false;
    }

}