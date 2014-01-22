<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\box\meta;

abstract class Action
{

    /**
     * Resolve query : read metadata and prepare action in Closure
     * @param $query
     * @throws \DomainException
     * @return array
     */
    public static function resolve($query)
    {
        // closure or function
        if($function = static::isFunction($query)) {
            $type = 'function';
            list($callable, $metadata) = static::resolveFunction($function);
        }
        // static method
        elseif($tuple = static::isStaticMethod($query)) {
            $type = 'static_method';
            list($callable, $metadata) = static::resolveStaticMethod($tuple);
        }
        // class method
        elseif($tuple = static::isClassMethod($query)) {
            $type = 'class_method';
            list($callable, $metadata) = static::resolveClassMethod($tuple);
            if(!is_object($callable[0])) {
                $callable[0] = new $callable[0]();
            }
        }
        else {
            throw new \DomainException('Action not valid.');
        }

        // return tuple
        return [$callable, $metadata, $type];
    }


    /**
     * Resolve function
     * @param $query
     * @return array
     */
    protected static function resolveFunction($query)
    {
        // apply reflection
        $function = new \ReflectionFunction($query);

        // get metadata
        $metadata = Annotation::get($function);

        return [$query, $metadata];
    }


    /**
     * Resolve static function
     * @param $query
     * @return array
     */
    protected static function resolveStaticMethod($query)
    {
        // parse string
        $parsed = is_string($query) ? explode('::', $query) : $query;

        // reflect class
        $class = new \ReflectionClass($parsed[0]);

        // reflect method
        $method = new \ReflectionMethod($parsed[0], $parsed[1]);

        // get metadata
        $metadata = array_merge(
            Annotation::get($class),
            Annotation::get($method)
        );

        // set callable
        $callable = $parsed;

        return [$callable, $metadata];
    }


    /**
     * Resolve class method
     * @param $query
     * @return array
     */
    protected static function resolveClassMethod($query)
    {
        // parse string
        $parsed = is_string($query) ? explode('::', $query) : $query;

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

        return [$callable, $metadata];
    }


    /**
     * Check if callable is function or Closure
     * @param $callable
     * @return callable|bool
     */
    public static function isFunction($callable)
    {
        if($callable instanceof \Closure or (is_string($callable) and function_exists($callable))) {
            return $callable;
        }

        return false;
    }


    /**
     * Check if callable if class method ([Class, method])
     * @param $callable
     * @return bool
     */
    public static function isStaticMethod($callable)
    {
        // parse string
        if(is_string($callable) and strpos($callable, '::') !== false) {
            $callable = explode('::', $callable);
        }

        // reflect tuple
        if(is_callable($callable) and is_array($callable) and count($callable) == 2) {
            $method = new \ReflectionMethod($callable[0], $callable[1]);
            if($method->isPublic() and $method->isStatic()) {
                return $callable;
            }
        }

        return false;
    }


    /**
     * Check if callable if object method ([Object, method])
     * @param $callable
     * @return bool
     */
    public static function isClassMethod($callable)
    {
        // parse string
        if(is_string($callable) and strpos($callable, '::') !== false) {
            $callable = explode('::', $callable);
        }

        // reflect tuple
        if(is_callable($callable) and is_array($callable) and count($callable) == 2) {
            $method = new \ReflectionMethod($callable[0], $callable[1]);
            if($method->isPublic() and !$method->isStatic() and !$method->isAbstract()) {
                return $callable;
            }
        }

        return false;
    }


    /**
     * Trigger action and return result
     * @param $callable
     * @param array $args
     * @param bool $resolve
     * @return mixed
     */
    public static function call($callable, array $args = [], $resolve = false)
    {
        // resolve query
        if($resolve) {
            list($callable) = static::resolve($callable);
        }

        // trigger action
        return call_user_func_array($callable, $args);
    }

} 