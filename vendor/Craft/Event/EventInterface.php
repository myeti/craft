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

interface EventInterface
{

    /**
     * Attach callback
     * @param string $event
     * @param callable $callback
     */
    public function on($event, callable $callback);

    /**
     * Attach listener
     * @param ListenerInterface $listener
     */
    public function attach(ListenerInterface $listener);

    /**
     * Detach all event callbacks
     * @param string $event
     */
    public function off($event);

    /**
     * Fire event
     * @param string $event
     * @param array $params
     * @return int
     */
    public function fire($event, array $params = []);

} 