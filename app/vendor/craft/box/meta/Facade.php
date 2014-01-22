<?php

namespace craft\box\meta;

use craft\box\text\String;
use craft\box\pattern\StaticSingleton;

trait Facade
{

    use StaticSingleton;

    /**
     * Redirect all method to instance
     * @param $method
     * @param array $args
     * @throws \BadMethodCallException
     * @return mixed
     */
    public static function __callStatic($method, array $args = [])
    {
        // unknown method
        if(!method_exists(static::instance(), $method)) {
            $message = String::compose('Uknown method :class:::method', [
                'class' => get_called_class(),
                'method' => $method
            ]);
            throw new \BadMethodCallException($message);
        }

        return forward_static_call_array([static::instance(), $method], $args);
    }

}