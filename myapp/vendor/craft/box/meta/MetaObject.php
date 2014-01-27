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

class MetaObject
{

    /**
     * Inheritance
     * @param null $extends
     */
    public function __construct($extends = null)
    {
        // inheritance ?
        if($extends) {

            // get ref
            $parent = new $extends();
            $ref = new \ReflectionObject($parent);

            // copy properties
            foreach($ref->getProperties() as $property) {
                $this->{$property->getName()} = $property->getValue($parent);
            }

            // copy methods
            foreach($ref->getMethods() as $method) {
                $this->{$method->getName()} = $method->getClosure($parent);
            }

        }
    }

    /**
     * Call user method
     * @param $method
     * @param array $args
     * @throws \BadMethodCallException
     * @return mixed
     */
    public function __call($method, array $args)
    {
        // undefined method
        if(!isset($this->{$method}) or !($this->{$method} instanceof \Closure)) {
            throw new \BadMethodCallException('Call to undefined method "' . $method . '"');
        }

        // add $this to args
        $that = &$this;
        array_unshift($args, $that);

        // execute callback
        return call_user_func_array($this->{$method}, $args);
    }

}