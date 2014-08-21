<?php

/**
 * This file is part of the Craft package.
 *
 * Copyright Aymeric Assier <aymeric.assier@gmail.com>
 *
 * For the full copyright and license information, please view the Licence.txt
 * file that was distributed with this source code.
 */
namespace Craft\App;

use Craft\Event;

abstract class Service implements Event\ListenerInterface
{

    /**
     * Get listening methods
     * @return array
     */
    abstract public function register();

    /**
     * Subscribe to subject's events
     * @param Event\ChannelInterface $channel
     */
    public function listen(Event\ChannelInterface $channel)
    {
        // get listening methods
        $events = (array)$this->register();

        // bind to subject
        foreach($events as $event => $method) {
            $channel->on($event, [$this, $method]);
        }
    }

}