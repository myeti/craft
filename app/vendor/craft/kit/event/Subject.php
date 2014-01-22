<?php
/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace craft\kit\event;

trait Subject
{

    /** @var array */
    protected $_events = [];


    /**
     * Attach callback
     * @param $name
     * @param \Closure|Observer $listener
     * @throws \InvalidArgumentException
     */
    public function on($name, $listener)
    {
        // type hint
        if(!is_callable($listener) and !($listener instanceof Observer)) {
            throw new \InvalidArgumentException('Event subject must attach Closure or Observer object.');
        }

        // create event repository
        if(!isset($this->_events[$name])){
            $this->_events[$name] = [];
        }

        // attach callback
        $this->_events[$name][] = $listener;
    }


    /**
     * Fire event
     * @param $name
     * @param array $args
     */
    public function fire($name, array $args = [])
    {
        // no listeners
        if(empty($this->_events[$name])) {
            return;
        }

        // fire * event
        if($name != '*') {
            $this->fire('*', ['name' => $name, 'args' => $args]);
        }

        // prepare event object
        $event = new Event($name, $args);

        // trigger all listeners
        foreach($this->_events[$name] as $listener){

            // callback
            if($listener instanceof \Closure) {
                call_user_func($listener, $event);
            }
            // observer
            elseif($listener instanceof Observer) {
                $listener->notify($event);
            }

        }
    }


}