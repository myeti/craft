<?php

namespace Craft\Event;

trait Delegate
{

    /** @var EventInterface */
    protected $subject;


    /**
     * Attach callback
     * @param string $event
     * @param callable $callback
     * @return $this
     */
    public function on($event, callable $callback)
    {
        $this->subject->on($event, $callback);
        return $this;
    }


    /**
     * Attach callback
     * @param ListenerInterface $listener
     * @return $this
     */
    public function attach(ListenerInterface $listener)
    {
        $listener->listen($this->subject);
        return $this;
    }


    /**
     * Detach all event listeners
     * @param $event
     * @return $this
     */
    public function off($event)
    {
        $this->subject->off($event);
        return $this;
    }


    /**
     * Event event
     * @param string $event
     * @param array $params
     * @return int
     */
    public function fire($event, array $params = [])
    {
        return $this->subject->fire($event, $params);
    }

} 