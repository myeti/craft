<?php

namespace Craft\Kit;

abstract class Object
{

    /**
     * Get class name
     * @param $object
     * @return string
     */
    public static function classname($object)
    {
        if(is_object($object)) {
            $object = get_class($object);
        }

        $segments = explode('\\', $object);
        return end($segments);
    }


    /**
     * Check if object is using trait
     * @param $object
     * @param $trait
     * @return bool
     */
    public static function hasTrait($object, $trait)
    {
        return in_array($trait, class_uses($object));
    }


    /**
     * Hydrate object props with array
     * @param $object
     * @param array $data
     * @return object
     */
    public static function hydrate($object, array $data)
    {
        foreach($data as $field => $value) {
            if(property_exists($object, $field)) {
                $object->{$field} = $value;
            }
        }

        return $object;
    }


    /**
     * Parse object meta
     * @param string|object $object
     * @param string $key
     * @return mixed
     */
    public static function classMeta($object, $key = null)
    {
        // reflector
        $reflector = is_object($object)
            ? new \ReflectionObject($object)
            : new \ReflectionClass($object);

        // parse @annotation
        preg_match_all('/@([a-zA-Z0-9]+) (.+)(\n|\*\/)/', $reflector->getDocComment(), $out, PREG_SET_ORDER);

        // sort
        $data = [];
        foreach($out as $match) {
            $data[$match[1]] = $match[2];
        }

        // return all or one annotation
        if($key) {
            return isset($data[$key]) ? $data[$key] : null;
        }

        return $data;
    }


    public static function methodMeta($object, $method, $key = null)
    {

    }


    public static function propertyMeta($object, $property, $key = null)
    {

    }

} 