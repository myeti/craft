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

} 