<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Event;

class Channel implements Trigger
{

    /** @var array */
    protected $listeners = [];


    /**
     * Attach callback
     * @param string $event
     * @param callable $callable
     * @return $this
     */
    public function on($event, callable $callable)
    {
        $this->listeners[$event][] = $callable;
        return $this;
    }


    /**
     * Attach listener
     * @param Listener $listener
     * @return $this
     */
    public function attach(Listener $listener)
    {
        $listener->listen($this);
        return $this;
    }


    /**
     * Clear listener list
     * @param string $event
     * @return $this
     */
    public function off($event)
    {
        $this->listeners[$event] = [];
        return $this;
    }


    /**
     * Fire event
     * @param string $event
     * @param mixed $params
     * @return int
     */
    public function fire($event, $params = null)
    {
        // no listeners
        if(!isset($this->listeners[$event])) {
            return false;
        }

        // get params
        $params = func_get_args();
        array_shift($params);

        // trigger all listeners
        $count = 0;
        foreach($this->listeners[$event] as $callable){
            call_user_func_array($callable, $params);
            $count++;
        }

        return $count;
    }

}