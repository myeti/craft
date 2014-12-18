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

abstract class Annot
{

    const REGEX = '/@([a-zA-Z0-9]+) (.+)(\*\/|\n)/';


    /**
     * Get class annotations
     * @param string|object $class
     * @param string $key
     * @return array|mixed
     */
    public static function object($class, $key = null)
    {
        $reflector = is_object($class) ? new \ReflectionObject($class) : new \ReflectionClass($class);
        return static::parse($reflector, $key);
    }


    /**
     * Get method annotations
     * @param string|object $class
     * @param string $method
     * @param string $key
     * @return array|mixed
     */
    public static function method($class, $method, $key = null)
    {
        $reflector = new \ReflectionMethod($class, $method);
        return static::parse($reflector, $key);
    }


    /**
     * Get property annotations
     * @param string|object $class
     * @param string $property
     * @param string $key
     * @return array|mixed
     */
    public static function property($class, $property, $key = null)
    {
        $reflector = new \ReflectionProperty($class, $property);
        return static::parse($reflector, $key);
    }


    /**
     * Get function annotations
     * @param \Closure $closure
     * @param string $key
     * @return array|mixed
     */
    public static function closure(\Closure $closure, $key = null)
    {
        $reflector = new \ReflectionFunction($closure);
        return static::parse($reflector, $key);
    }


    /**
     * Get annotation from Reflector object
     * @param $reflector
     * @param $annotation
     * @return array|mixed
     */
    public static function parse(\Reflector $reflector, $annotation = null)
    {
        // cannot read phpdoc
        if(!method_exists($reflector, 'getDocComment')) {
            return false;
        }

        // parse @annotation
        preg_match_all(static::REGEX, $reflector->getDocComment(), $out, PREG_SET_ORDER);

        // sort
        $data = [];
        foreach($out as $match) {
            $data[$match[1]] = $match[2];
        }

        // return all or one annotation
        if($annotation) {
            return isset($data[$annotation]) ? $data[$annotation] : null;
        }

        return $data;
    }

}