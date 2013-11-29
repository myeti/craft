<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 *
 * @author Aymeric Assier <aymeric.assier@gmail.com>
 * @date 2013-09-12
 * @version 0.1
 */
namespace craft\meta;

trait Events
{

    /** @var array */
    protected $_events = [];

    /**
     * Listen event
     * @param $name
     * @param callable $callback
     */
    public function on($name, \Closure $callback)
    {
        // create event repository
        if(!isset($this->_events[$name])){
            $this->_events[$name] = [];
        }

        // attach callback
        $this->_events[$name][] = $callback;
    }

    /**
     * Fire event
     * @param $name
     * @param array $args
     */
    public function fire($name, array $args = [])
    {
        // no callbacks
        if(!isset($this->_events[$name])) {
            return;
        }

        // $this as the first arg
        $ref = &$this;
        array_unshift($args, $ref);

        // trigger all callbacks
        foreach($this->_events[$name] as $callback){
            call_user_func_array($callback, $args);
        }
    }

}