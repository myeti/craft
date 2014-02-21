<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\Reflect;

trait Event
{

    /** @var array */
    protected $listeners = [];

    /** @var array */
    protected $locked = [];


    /**
     * Attach callback
     * @param string $event
     * @param callable $callable
     * @param string $key
     * @throws \LogicException
     * @return $this
     */
    public function on($event, callable $callable, $key = null)
    {
        // locked, cannot attach
        if(isset($this->locked[$event]) and !$this->locked[$event] == $key) {
            throw new \LogicException('Event "' . $event . '" is locked.');
        }

        $this->listeners[$event][] = $callable;
        return $this;
    }


    /**
     * Lock event attaching
     * @param $event
     * @param $key
     * @return $this
     */
    protected function lock($event, $key = null)
    {
        $this->locked[$event] = $key ?: uniqid();
        return $this;
    }


    /**
     * Unlock attaching
     * @param $event
     * @return $this
     */
    protected function unlock($event)
    {
        unset($this->locked[$event]);
        return $this;
    }


    /**
     * Fire event
     * @param string $event
     * @param array $params
     * @return int
     */
    protected function fire($event, array $params = [])
    {
        // fire * event
        if($event != '*') {
            array_unshift($params, $event);
            $this->fire('*', $params);
        }

        // no listeners
        if(!isset($this->listeners[$event])) {
            return false;
        }

        // trigger all listeners
        foreach($this->listeners[$event] as $callable){
            call_user_func_array($callable, $params);
        }

        // total triggered
        return count($this->listeners[$event]);
    }

}