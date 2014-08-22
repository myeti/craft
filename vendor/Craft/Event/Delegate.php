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

trait Delegate
{

    /** @var Trigger */
    protected $channel;


    /**
     * Attach callback
     * @param string $event
     * @param callable $callback
     * @return $this
     */
    public function on($event, callable $callback)
    {
        $this->channel->on($event, $callback);
        return $this;
    }


    /**
     * Attach callback
     * @param Listener $listener
     * @return $this
     */
    public function attach(Listener $listener)
    {
        $listener->listen($this->channel);
        return $this;
    }


    /**
     * Detach all event listeners
     * @param $event
     * @return $this
     */
    public function off($event)
    {
        $this->channel->off($event);
        return $this;
    }


    /**
     * Event event
     * @param string $event
     * @param mixed $params
     * @return int
     */
    public function fire($event, $params = null)
    {
        return call_user_func_array([$this->channel, 'fire'], func_get_args());
    }

} 