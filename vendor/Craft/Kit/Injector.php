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

class Injector implements InjectorInterface
{

    /** @var array */
    protected $factories = [];


    /**
     * Check if instance has definition
     * @param string $class
     * @return bool
     */
    public function has($class)
    {
        return isset($this->factories[$class]);
    }


    /**
     * Store instance
     * @param string $class
     * @param object $instance
     * @return $this
     */
    public function store($class, &$instance)
    {
        $this->factories[$class] = function() use(&$instance)
        {
            return $instance;
        };

        return $this;
    }


    /**
     * Set user factory
     * @param string $class
     * @param callable $factory
     * @param bool $singleton
     * @return $this
     */
    public function define($class, callable $factory, $singleton = false)
    {
        if(!$singleton) {
            $this->factories[$class] = $factory;
        }
        else {
            $this->factories[$class] = function(...$params) use($factory)
            {
                static $instance;
                if(!$instance) {
                    $instance = $factory(...$params);
                }

                return $instance;
            };
        }

        return $this;
    }


    /**
     * Make class instance
     * @param $class
     * @param array $params
     * @return object
     */
    public function make($class, array $params = [])
    {
        // no factory
        if(!$this->has($class)) {
            return false;
        }

        return call_user_func_array($this->factories[$class], $params);
    }

} 